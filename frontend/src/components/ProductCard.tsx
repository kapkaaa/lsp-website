import React from 'react';
import { Link } from 'react-router-dom';
import { ShoppingCartIcon } from '@heroicons/react/24/outline';
import { formatCurrency } from '../utils/formatters';
import Badge from './Badge';

interface ProductCardProps {
    id: number;
    name: string;
    brand: string;
    type: string;
    price: number;
    image: string;
    stock: number;
    onAddToCart?: () => void;
}

const ProductCard: React.FC<ProductCardProps> = ({
    id,
    name,
    brand,
    type,
    price,
    image,
    stock,
    onAddToCart,
}) => {
    const isOutOfStock = stock === 0;

    return (
        <div className="card card-hover group animate-fade-in-up">
            <Link to={`/product/${id}`}>
                <div className="image-zoom-container relative">
                    <img
                        src={image}
                        alt={name}
                        className="w-full h-56 object-cover image-zoom"
                    />
                    {isOutOfStock && (
                        <div className="absolute inset-0 bg-black/60 flex items-center justify-center">
                            <Badge variant="error" className="text-base px-4 py-2">
                                Stok Habis
                            </Badge>
                        </div>
                    )}
                    {!isOutOfStock && stock < 10 && (
                        <div className="absolute top-3 right-3">
                            <Badge variant="warning">Stok Terbatas</Badge>
                        </div>
                    )}
                </div>
            </Link>

            <div className="p-4 space-y-3">
                <Link to={`/product/${id}`}>
                    <div className="space-y-1">
                        <p className="text-sm text-gray-500">{brand} • {type}</p>
                        <h3 className="font-heading font-semibold text-gray-900 line-clamp-2 group-hover:text-cyan-600 transition-colors">
                            {name}
                        </h3>
                    </div>
                </Link>

                <div className="flex items-center justify-between">
                    <div>
                        <p className="text-2xl font-bold text-cyan-600">
                            {formatCurrency(price)}
                        </p>
                        <p className="text-xs text-gray-500">Stok: {stock}</p>
                    </div>

                    {!isOutOfStock && onAddToCart && (
                        <button
                            onClick={(e) => {
                                e.preventDefault();
                                onAddToCart();
                            }}
                            className="p-3 rounded-lg bg-cyan-500 text-white hover:bg-cyan-600 transition-colors shadow-md hover:shadow-lg"
                            aria-label="Add to cart"
                        >
                            <ShoppingCartIcon className="h-5 w-5" />
                        </button>
                    )}
                </div>
            </div>
        </div>
    );
};

export default ProductCard;
