/**
 * FAQ Management — admin CRUD for frequently asked questions displayed on the home page.
 */
import React, { useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import { useAlert } from '@/Contexts/AlertContext';
import Footer from '@/Components/Footer';

export default function Faqs({ auth, faqs }) {
    // State — modal visibility, editing target, and FAQ form
    const { showConfirm }                                                 = useAlert();
    const [editingFaq, setEditingFaq]                                                = useState(null);
    const [isModalOpen, setIsModalOpen]                                              = useState(false);
    const { data, setData, post, patch, delete: destroy, processing, errors, reset } = useForm({
        question: '',
        answer  : '',
        order   : 0,
    });

    // Handlers — modal open/close, create/update/delete
    const openCreateModal = () => {
        setEditingFaq(null);
        reset();
        setIsModalOpen(true);
    };

    const openEditModal = (faq) => {
        setEditingFaq(faq);
        setData({
            question: faq.question,
            answer  : faq.answer,
            order   : faq.order || 0,
        });
        setIsModalOpen(true);
    };

    const closeModal = () => {
        setIsModalOpen(false);
        setEditingFaq(null);
        reset();
    };

    // Form submission — PATCH when editing, POST when creating
    const handleSubmit = (e) => {
        e.preventDefault();
        if (editingFaq) {
            patch(route('admin.faqs.update', editingFaq.id), {
                onSuccess: () => {
                    closeModal();
                },
            });
        } else {
            post(route('admin.faqs.store'), {
                onSuccess: () => {
                    closeModal();
                },
            });
        }
    };

    const handleDelete = async (faq) => {
        const confirmed = await showConfirm({
            type       : 'danger',
            title      : 'Delete FAQ',
            message    : `Are you sure you want to delete this FAQ? This action cannot be undone.`,
            confirmText: 'Delete FAQ',
        });

        if (confirmed) {
            destroy(route('admin.faqs.destroy', faq.id), {
                onSuccess: () => {},
            });
        }
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h2 className="text-4xl font-black text-slate-900 dark:text-white tracking-tight italic uppercase">
                            FAQ <span className="text-teal-900 dark:text-lime-400">Management</span>
                        </h2>
                        <p className="mt-2 text-slate-600 dark:text-slate-400 font-medium tracking-wide">
                            Configure and maintain frequently asked questions for the platform.
                        </p>
                    </div>
                </div>
            }
        >
            <Head title="FAQ Management" />

            <div className="space-y-6">
                <div className="flex justify-start">
                    <button
                        onClick={openCreateModal}
                        className="inline-flex items-center gap-3 px-8 py-4 bg-teal-900 dark:bg-lime-500 text-white dark:text-[#102824] rounded-[2rem] font-black text-sm uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl shadow-teal-900/20 dark:shadow-lime-500/10"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Add New FAQ
                    </button>
                </div>

                {/* FAQ list */}
                <div className="grid grid-cols-1 gap-6">
                    {faqs.length > 0 ? faqs.map((faq) => (
                        <div
                            key={faq.id}
                            className="fauna-panel p-8 group hover:border-lime-500/30 transition-all duration-500"
                        >
                            <div className="flex justify-between items-start gap-6">
                                <div className="flex-1">
                                    <div className="flex items-center gap-4 mb-3">
                                        <span className="px-3 py-1 rounded-full bg-teal-900/10 dark:bg-lime-500/10 text-teal-900 dark:text-lime-400 text-[10px] font-black uppercase tracking-widest border border-teal-900/20 dark:border-lime-500/20">
                                            Order: {faq.order || 0}
                                        </span>
                                    </div>
                                    <h3 className="text-xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">
                                        {faq.question}
                                    </h3>
                                    <p className="text-slate-600 dark:text-slate-400 leading-relaxed font-medium">
                                        {faq.answer}
                                    </p>
                                </div>
                                <div className="flex items-center gap-2 shrink-0">
                                    <button
                                        onClick={() => openEditModal(faq)}
                                        className="p-3 rounded-2xl bg-slate-100 dark:bg-[#18342f] text-slate-600 dark:text-slate-400 hover:text-teal-900 dark:hover:text-lime-400 transition-all border border-transparent hover:border-teal-900/20"
                                        title="Edit FAQ"
                                    >
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button
                                        onClick={() => handleDelete(faq)}
                                        className="p-3 rounded-2xl bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-sm"
                                        title="Delete FAQ"
                                    >
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    )) : (
                        <div className="fauna-panel p-20 text-center">
                            <div className="w-20 h-20 rounded-[2rem] bg-slate-100 dark:bg-[#18342f] flex items-center justify-center mx-auto mb-6">
                                <svg className="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 className="text-2xl font-black text-slate-900 dark:text-white mb-2 uppercase italic tracking-tighter">No FAQs Found</h3>
                            <p className="text-slate-600 dark:text-slate-400 max-w-sm mx-auto font-medium">
                                Start by adding frequently asked questions to help your users.
                            </p>
                        </div>
                    )}
                </div>
            </div>

            <Footer />

            {/* Modal */}
            {isModalOpen && (
                <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-slate-950/40 backdrop-blur-md animate-in fade-in duration-300">
                    <div className="w-full max-w-2xl fauna-panel p-6 sm:p-10 bg-white/95 dark:bg-[#102824]/95 animate-in zoom-in-95 duration-300 border-lime-500/20 overflow-y-auto max-h-[90vh]">
                        <div className="flex justify-between items-center mb-10">
                            <h3 className="text-3xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter">
                                {editingFaq ? 'Edit' : 'Create'} <span className="text-teal-900 dark:text-lime-400">FAQ</span>
                            </h3>
                            <button onClick={closeModal} className="p-3 rounded-2xl bg-slate-100 dark:bg-[#18342f] text-slate-500 hover:text-rose-500 transition-colors">
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <form onSubmit={handleSubmit} className="space-y-8">
                            <div className="space-y-3">
                                <label className="text-[10px] font-black text-teal-900 dark:text-lime-400 uppercase tracking-[0.3em] block">Question</label>
                                <input
                                    type="text"
                                    disabled={processing}
                                    value={data.question}
                                    onChange={e => setData('question', e.target.value)}
                                    className="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-[#18342f] border-emerald-900/10 dark:border-[#28524a] focus:ring-2 focus:ring-lime-500 transition-all font-bold dark:text-white"
                                    placeholder="Enter the question..."
                                />
                                {errors.question && <p className="text-rose-500 text-[10px] font-black uppercase mt-2 tracking-widest">{errors.question}</p>}
                            </div>

                            <div className="space-y-3">
                                <label className="text-[10px] font-black text-teal-900 dark:text-lime-400 uppercase tracking-[0.3em] block">Answer</label>
                                <textarea
                                 disabled={processing}
                                    value={data.answer}
                                    onChange={e => setData('answer', e.target.value)}
                                    className="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-[#18342f] border-emerald-900/10 dark:border-[#28524a] focus:ring-2 focus:ring-lime-500 transition-all font-medium dark:text-white min-h-[150px]"
                                    placeholder="Enter the answer..."
                                />
                                {errors.answer && <p className="text-rose-500 text-[10px] font-black uppercase mt-2 tracking-widest">{errors.answer}</p>}
                            </div>

                            <div className="space-y-3">
                                <label className="text-[10px] font-black text-teal-900 dark:text-lime-400 uppercase tracking-[0.3em] block">Display Order</label>
                                <input
                                 disabled={processing}
                                    type="number"
                                    value={data.order}
                                    onChange={e => setData('order', e.target.value)}
                                    className="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-[#18342f] border-emerald-900/10 dark:border-[#28524a] focus:ring-2 focus:ring-lime-500 transition-all font-black dark:text-white"
                                />
                            </div>

                            <div className="flex gap-4 pt-4">
                                <button
                                    type="button"
                                    onClick={closeModal}
                                    className="flex-1 py-5 px-8 rounded-[2rem] bg-slate-100 dark:bg-[#18342f] text-slate-600 dark:text-slate-400 font-black uppercase tracking-widest hover:bg-slate-200 transition-all text-sm"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="flex-[2] py-5 px-8 bg-teal-900 dark:bg-lime-500 text-white dark:text-[#102824] rounded-[2rem] font-black uppercase tracking-widest hover:scale-[1.02] active:scale-[0.98] transition-all shadow-xl shadow-teal-900/20 dark:shadow-lime-500/10 disabled:opacity-50 text-sm"
                                >
                                    {processing ? 'Saving...' : (editingFaq ? 'Update FAQ' : 'Create FAQ')}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AuthenticatedLayout>
    );
}
