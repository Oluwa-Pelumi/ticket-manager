import React, { createContext, useContext, useState, useCallback } from 'react';

const AlertContext = createContext();

export const useAlert = () => {
    const context = useContext(AlertContext);
    if (!context) {
        throw new Error('useAlert must be used within an AlertProvider');
    }
    return context;
};

export const AlertProvider = ({ children }) => {
    const [alerts, setAlerts]   = useState([]);
    const [confirm, setConfirm] = useState(null);

    const showAlert = useCallback((message, type = 'success', duration = 5000) => {
        const id = Math.random().toString(36).substr(2, 9);
        setAlerts((prev) => [...prev, { id, message, type }]);
        
        setTimeout(() => {
            setAlerts((prev) => prev.filter((alert) => alert.id !== id));
        }, duration);
    }, []);

    const showConfirm = useCallback((config) => {
        return new Promise((resolve) => {
            setConfirm({
                ...config,
                resolve: (value) => {
                    setConfirm(null);
                    resolve(value);
                },
                cancel: () => {
                    setConfirm(null);
                    resolve(false);
                }
            });
        });
    }, []);

    const removeAlert = (id) => {
        setAlerts((prev) => prev.filter((alert) => alert.id !== id));
    };

    return (
        <AlertContext.Provider value={{ showAlert, showConfirm, alerts, confirm, removeAlert }}>
            {children}
        </AlertContext.Provider>
    );
};
