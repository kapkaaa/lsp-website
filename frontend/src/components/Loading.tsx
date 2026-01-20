import React from 'react';

interface LoadingProps {
    size?: 'sm' | 'md' | 'lg';
    text?: string;
}

const Loading: React.FC<LoadingProps> = ({ size = 'md', text }) => {
    const sizeClasses = {
        sm: 'h-6 w-6',
        md: 'h-12 w-12',
        lg: 'h-16 w-16',
    };

    return (
        <div className="flex flex-col items-center justify-center gap-4 py-8">
            <div className="relative">
                <div
                    className={`${sizeClasses[size]} border-4 border-cyan-200 border-t-cyan-600 rounded-full animate-spin`}
                ></div>
            </div>
            {text && <p className="text-gray-600 animate-pulse">{text}</p>}
        </div>
    );
};

export default Loading;

// Skeleton Loader Component
export const Skeleton: React.FC<{ className?: string }> = ({ className = '' }) => {
    return <div className={`skeleton ${className}`}></div>;
};

// Product Card Skeleton
export const ProductCardSkeleton: React.FC = () => {
    return (
        <div className="card p-4 space-y-4 animate-pulse">
            <div className="w-full h-48 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200 rounded-lg animate-shimmer"></div>
            <div className="h-4 w-3/4 bg-gray-200 rounded"></div>
            <div className="h-4 w-1/2 bg-gray-200 rounded"></div>
            <div className="h-8 w-full bg-gray-200 rounded"></div>
        </div>
    );
};

// Product Grid Loading with Text
export const ProductGridLoading: React.FC<{ count?: number; columns?: 3 | 4 }> = ({ count = 8, columns = 4 }) => {
    const gridCols = columns === 3 ? 'lg:grid-cols-3' : 'lg:grid-cols-4';
    return (
        <div className="space-y-6">
            <div className="flex items-center justify-center gap-3 py-4">
                <div className="h-8 w-8 border-4 border-cyan-200 border-t-cyan-600 rounded-full animate-spin"></div>
                <p className="text-gray-600 font-medium animate-pulse">Memuat produk...</p>
            </div>
            <div className={`grid grid-cols-1 sm:grid-cols-2 ${gridCols} gap-6`}>
                {[...Array(count)].map((_, i) => (
                    <ProductCardSkeleton key={i} />
                ))}
            </div>
        </div>
    );
};
