import React, { useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { useAlert } from '@/Contexts/AlertContext';
import { useTheme } from '@/Contexts/ThemeContext';

const AlertSystem = () => {
    const { theme }                        = useTheme();
    const { alerts, confirm, removeAlert } = useAlert();

    return (
        <>
            {/* Toasts Container */}
            <div className="fixed bottom-8 right-8 z-[100] flex flex-col gap-4 pointer-events-none">
                {alerts.map((alert) => (
                    <div
                        key={alert.id}
                        className={`pointer-events-auto flex items-center gap-4 p-5 rounded-[2rem] border backdrop-blur-2xl shadow-2xl animate-in slide-in-from-right-10 fade-in duration-500 min-w-[320px] max-w-md
                            ${theme === 'dark' 
                                ? 'bg-slate-900/80 border-slate-800 text-white' 
                                : 'bg-white/80 border-slate-200 text-slate-900'
                            }`}
                    >
                        <div className={`p-3 rounded-2xl ${
                            alert.type === 'success' ? 'bg-emerald-500/20 text-emerald-500' :
                            alert.type === 'error' ? 'bg-rose-500/20 text-rose-500' :
                            alert.type === 'warning' ? 'bg-amber-500/20 text-amber-500' :
                            'bg-blue-500/20 text-blue-500'
                        }`}>
                            {alert.type === 'success' && <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"/></svg>}
                            {alert.type === 'error' && <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"/></svg>}
                            {alert.type === 'warning' && <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>}
                            {alert.type === 'info' && <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>}
                        </div>
                        <div className="flex-1">
                            <p className="text-sm font-bold tracking-tight">{alert.message}</p>
                        </div>
                        <button
                            onClick={() => removeAlert(alert.id)}
                            className="p-1 hover:bg-slate-500/10 rounded-lg transition-colors"
                        >
                            <svg className="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                ))}
            </div>

            {/* Confirmation Modal */}
            {confirm && (
                <div className="fixed inset-0 z-[110] flex items-center justify-center p-6 bg-slate-950/40 backdrop-blur-md animate-in fade-in duration-300">
                    <div 
                        className={`w-full max-w-md p-8 rounded-[3rem] border shadow-2xl animate-in zoom-in-95 duration-300
                            ${theme === 'dark' 
                                ? 'bg-slate-900/90 border-slate-800 text-white' 
                                : 'bg-white/90 border-slate-200 text-slate-900'
                            }`}
                    >
                        <div className={`inline-flex p-4 rounded-[1.5rem] mb-6 ${
                            confirm.type === 'danger' ? 'bg-rose-500/20 text-rose-500' : 'bg-blue-500/20 text-blue-500'
                        }`}>
                            {confirm.type === 'danger' ? (
                                <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            ) : (
                                <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            )}
                        </div>
                        
                        <h3 className="text-2xl font-black tracking-tight mb-2 uppercase italic">{confirm.title || 'Are you sure?'}</h3>
                        <p className="text-slate-500 dark:text-slate-400 mb-8 leading-relaxed font-medium">
                            {confirm.message || 'This action cannot be undone. Please confirm to proceed.'}
                        </p>

                        <div className="flex gap-4">
                            <button
                                onClick={confirm.cancel}
                                className={`flex-1 py-4 px-6 rounded-2xl font-bold transition-all
                                    ${theme === 'dark' 
                                        ? 'bg-slate-800 text-slate-300 hover:bg-slate-700' 
                                        : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                                    }`}
                            >
                                Cancel
                            </button>
                            <button
                                onClick={() => confirm.resolve(true)}
                                className={`flex-[2] py-4 px-6 rounded-2xl text-white font-bold shadow-xl transition-all hover:scale-[1.02] active:scale-[0.98]
                                    ${confirm.type === 'danger' 
                                        ? 'bg-rose-500 shadow-rose-500/20' 
                                        : 'bg-[#FF2D20] shadow-[#FF2D20]/20'
                                    }`}
                            >
                                {confirm.confirmText || 'Confirm Action'}
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </>
    );
};

export default AlertSystem;
