import Swal from 'sweetalert2';
import withReactContent from 'sweetalert2-react-content';

const MySwal = withReactContent(Swal);

/**
 * Toast notification for quick messages
 */
export const toast = MySwal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

/**
 * Alert utility for consistent styling
 */
export const alert = {
    success: (title: string, text?: string) => {
        return MySwal.fire({
            icon: 'success',
            title,
            text,
            confirmButtonColor: '#0891b2', // cyan-600
        });
    },
    error: (title: string, text?: string) => {
        return MySwal.fire({
            icon: 'error',
            title,
            text,
            confirmButtonColor: '#0891b2',
        });
    },
    warning: (title: string, text?: string) => {
        return MySwal.fire({
            icon: 'warning',
            title,
            text,
            confirmButtonColor: '#0891b2',
        });
    },
    info: (title: string, text?: string) => {
        return MySwal.fire({
            icon: 'info',
            title,
            text,
            confirmButtonColor: '#0891b2',
        });
    },
    confirm: (title: string, text: string, confirmButtonText = 'Ya, Lanjutkan') => {
        return MySwal.fire({
            title,
            text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0891b2',
            cancelButtonColor: '#6b7280',
            confirmButtonText,
            cancelButtonText: 'Batal'
        });
    }
};

export default MySwal;
