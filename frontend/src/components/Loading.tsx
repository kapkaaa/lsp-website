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
        <div className="card p-4 space-y-4">
            <Skeleton className="w-full h-48" />
            <Skeleton className="h-4 w-3/4" />
            <Skeleton className="h-4 w-1/2" />
            <Skeleton className="h-8 w-full" />
        </div>
    );
};
