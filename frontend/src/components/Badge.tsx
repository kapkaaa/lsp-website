import React from 'react';

interface BadgeProps {
    variant?: 'primary' | 'success' | 'warning' | 'error' | 'info';
    children: React.ReactNode;
    className?: string;
}

const Badge: React.FC<BadgeProps> = ({
    variant = 'primary',
    children,
    className = ''
}) => {
    const variantClasses = {
        primary: 'badge-primary',
        success: 'badge-success',
        warning: 'badge-warning',
        error: 'badge-error',
        info: 'badge-info',
    };

    return (
        <span className={`badge ${variantClasses[variant]} ${className}`}>
            {children}
        </span>
    );
};

export default Badge;
