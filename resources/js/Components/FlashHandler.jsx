// Bridges Inertia flash messages to the global alert toast system.
import { useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { useAlert } from '@/Contexts/AlertContext';

const FlashHandler = () => {
    // Hooks: alert API and current page flash props
    const { showAlert } = useAlert();
    const { flash }     = usePage().props;

    // Effect: display toasts when server sends flash messages
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

    // Render: headless component, no UI
    return null;
};

export default FlashHandler;




