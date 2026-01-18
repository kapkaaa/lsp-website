// Email validation
export const isValidEmail = (email: string): boolean => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
};

// Phone number validation (Indonesian format)
export const isValidPhone = (phone: string): boolean => {
    const phoneRegex = /^(\+62|62|0)[0-9]{9,12}$/;
    return phoneRegex.test(phone.replace(/[\s-]/g, ''));
};

// NIK validation (16 digits)
export const isValidNIK = (nik: string): boolean => {
    return /^\d{16}$/.test(nik);
};

// Password strength validation
export const validatePassword = (password: string): {
    isValid: boolean;
    errors: string[];
} => {
    const errors: string[] = [];

    if (password.length < 8) {
        errors.push('Password minimal 8 karakter');
    }
    if (!/[A-Z]/.test(password)) {
        errors.push('Password harus mengandung huruf besar');
    }
    if (!/[a-z]/.test(password)) {
        errors.push('Password harus mengandung huruf kecil');
    }
    if (!/[0-9]/.test(password)) {
        errors.push('Password harus mengandung angka');
    }

    return {
        isValid: errors.length === 0,
        errors,
    };
};

// Required field validation
export const isRequired = (value: string): boolean => {
    return value.trim().length > 0;
};

// Min length validation
export const minLength = (value: string, min: number): boolean => {
    return value.length >= min;
};

// Max length validation
export const maxLength = (value: string, max: number): boolean => {
    return value.length <= max;
};

// Number validation
export const isNumber = (value: string): boolean => {
    return !isNaN(Number(value)) && value.trim() !== '';
};

// Positive number validation
export const isPositiveNumber = (value: number): boolean => {
    return value > 0;
};
