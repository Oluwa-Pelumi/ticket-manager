import { useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { useAlert } from '@/Contexts/AlertContext';

const FlashHandler = () => {
    const { showAlert } = useAlert();
    const { flash }     = usePage().props;

    useEffect(() => {
        if (flash.success) {
            showAlert(flash.success, 'success');
        }
        if (flash.error) {
            showAlert(flash.error, 'error');
        }
        if (flash.warning) {
            showAlert(flash.warning, 'warning');
        }
        if (flash.info) {
            showAlert(flash.info, 'info');
        }
    }, [flash, showAlert]);

    return null;
};

export default FlashHandler;




