import React, { InputHTMLAttributes, useState } from 'react';

interface InputProps extends InputHTMLAttributes<HTMLInputElement> {
    label?: string;
    error?: string;
    icon?: React.ReactNode;
}

const Input: React.FC<InputProps> = ({
    label,
    error,
    icon,
    className = '',
    type = 'text',
    ...props
}) => {
    const [isFocused, setIsFocused] = useState(false);
    const hasValue = props.value || props.defaultValue;

    return (
        <div className="w-full">
            <div className="relative">
                {icon && (
                    <div className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        {icon}
                    </div>
                )}
                <input
                    type={type}
                    className={`input ${error ? 'input-error' : ''} ${icon ? 'pl-10' : ''} ${label ? 'pt-6 pb-2' : ''
                        } ${className}`}
                    onFocus={() => setIsFocused(true)}
                    onBlur={() => setIsFocused(false)}
                    {...props}
                />
                {label && (
                    <label
                        className={`absolute left-4 transition-all duration-200 pointer-events-none ${isFocused || hasValue
                                ? 'top-2 text-xs text-cyan-600'
                                : 'top-1/2 -translate-y-1/2 text-base text-gray-500'
                            }`}
                    >
                        {label}
                    </label>
                )}
            </div>
            {error && <p className="mt-1 text-sm text-red-600">{error}</p>}
        </div>
    );
};

export default Input;
