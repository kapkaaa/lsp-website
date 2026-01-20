import React, { createContext, useContext, useEffect, useState, useCallback } from 'react';
import apiClient from '../services/apiClient';
import { useUser } from './UserContext';

interface CartContextType {
    cartCount: number;
    refreshCart: () => Promise<void>;
    isAnimating: boolean;
    triggerAnimation: () => void;
}

const CartContext = createContext<CartContextType | undefined>(undefined);

export const CartProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const { user } = useUser();
    const [cartCount, setCartCount] = useState(0);
    const [isAnimating, setIsAnimating] = useState(false);

    const fetchCartCount = useCallback(async () => {
        if (!user) {
            setCartCount(0);
            return;
        }

        try {
            const response = await apiClient.get('/cart');
            const data = response.data.data;
            const items = Array.isArray(data) ? data : (data?.items || []);
            // Calculate total quantity across all items
            const totalCount = items.reduce((sum: number, item: any) => sum + (item.quantity || 0), 0);
            setCartCount(totalCount);
        } catch (error) {
            console.error('Failed to fetch cart count:', error);
            setCartCount(0);
        }
    }, [user]);

    // Fetch cart count on mount and when user changes
    useEffect(() => {
        fetchCartCount();
    }, [fetchCartCount]);

    const refreshCart = useCallback(async () => {
        await fetchCartCount();
    }, [fetchCartCount]);

    const triggerAnimation = useCallback(() => {
        setIsAnimating(true);
        // Reset animation after it completes
        setTimeout(() => {
            setIsAnimating(false);
        }, 600);
    }, []);

    return (
        <CartContext.Provider value={{ cartCount, refreshCart, isAnimating, triggerAnimation }}>
            {children}
        </CartContext.Provider>
    );
};

export const useCart = () => {
    const context = useContext(CartContext);
    if (context === undefined) {
        throw new Error('useCart must be used within a CartProvider');
    }
    return context;
};

export { CartContext };
