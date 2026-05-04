import { useState } from 'react';
import { useAlert } from '@/Contexts/AlertContext';
import FlashHandler from '@/Components/FlashHandler';
import { Head, router, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Categories({ auth, categories }) {
    const { showAlert, showConfirm } = useAlert();
    const [editingCategory, setEditingCategory] = useState(null);
    
    const { data, setData, post, patch, processing, errors, reset, clearErrors } = useForm({
        name: '',
        group: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        if (editingCategory) {
            patch(route('admin.categories.update', editingCategory.id), {
                onSuccess: () => {
                    setEditingCategory(null);
                    reset();
                    showAlert({ type: 'success', title: 'Success', message: 'Category updated successfully!' });
                },
            });
        } else {
            post(route('admin.categories.store'), {
                onSuccess: () => {
                    reset();
                    showAlert({ type: 'success', title: 'Success', message: 'Category created successfully!' });
                },
            });
        }
    };

    const handleEdit = (category) => {
        setEditingCategory(category);
        setData({
            name: category.name,
            group: category.group || '',
        });
        clearErrors();
    };

    const handleCancelEdit = () => {
        setEditingCategory(null);
        reset();
        clearErrors();
    };

    const handleDelete = async (category) => {
        const confirmed = await showConfirm({
            type: 'danger',
            title: 'Delete Category',
            confirmText: 'Delete Category',
            message: `Are you sure you want to delete "${category.name}"? This will affect tickets linked to this category.`,
        });

        if (confirmed) {
            router.delete(route('admin.categories.destroy', category.id), {
                onSuccess: () => showAlert({ type: 'success', title: 'Deleted', message: 'Category removed.' }),
            });
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center gap-4">
                    <div className="w-12 h-12 rounded-2xl bg-teal-900 flex items-center justify-center shadow-lg border border-white/20">
                        <svg className="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2.5}>
                            <path strokeLinecap="round" strokeLinejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div className="flex flex-col">
                        <h2 className="text-xl font-black text-slate-900 dark:text-white tracking-tight">
                            Category Portal
                        </h2>
                        <span className="text-[10px] font-black tracking-[0.3em] text-slate-400">Manage Support Topics</span>
                    </div>
                </div>
            }
        >
            <Head title="Manage Categories" />

            <div className="max-w-7xl mx-auto py-12 px-6">
                
                <FlashHandler />

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Category Form */}
                    <div className="lg:col-span-1">
                        <div className="fauna-panel p-8 sticky top-24">
                            <h3 className="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">
                                {editingCategory ? 'Edit Category' : 'Create New Category'}
                            </h3>
                            
                            <form onSubmit={handleSubmit} className="space-y-6">
                                <div className="space-y-2">
                                    <label className="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400 uppercase">Category Name</label>
                                    <input
                                        type="text"
                                        value={data.name}
                                        onChange={e => setData('name', e.target.value)}
                                        className="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-medium"
                                        placeholder="e.g. Prescription Issues"
                                        required
                                    />
                                    {errors.name && <p className="text-rose-500 text-[10px] font-bold">{errors.name}</p>}
                                </div>

                                <div className="space-y-2">
                                    <label className="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400 uppercase">Group Name (Optional)</label>
                                    <input
                                        type="text"
                                        value={data.group}
                                        onChange={e => setData('group', e.target.value)}
                                        className="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 transition-all outline-none font-medium"
                                        placeholder="e.g. Pharmacy Services"
                                    />
                                    {errors.group && <p className="text-rose-500 text-[10px] font-bold">{errors.group}</p>}
                                </div>

                                <div className="flex flex-col gap-3">
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="w-full py-4 rounded-xl bg-teal-900 text-white font-black text-sm tracking-widest shadow-lg hover:bg-lime-500 hover:text-teal-900 transition-all disabled:opacity-50"
                                    >
                                        {editingCategory ? 'UPDATE CATEGORY' : 'CREATE CATEGORY'}
                                    </button>
                                    
                                    {editingCategory && (
                                        <button
                                            type="button"
                                            onClick={handleCancelEdit}
                                            className="w-full py-3 rounded-xl border border-emerald-900/10 dark:border-[#1d3a34] text-slate-600 font-black text-[10px] tracking-widest hover:bg-emerald-50/50 dark:hover:bg-slate-800 transition-all"
                                        >
                                            CANCEL EDIT
                                        </button>
                                    )}
                                </div>
                            </form>
                        </div>
                    </div>

                    {/* Categories List */}
                    <div className="lg:col-span-2">
                        <div className="overflow-hidden rounded-[2.5rem] bg-white/50 dark:bg-[#102824]/70 backdrop-blur-md border border-emerald-900/10/50 dark:border-[#1d3a34] shadow-2xl">
                            <table className="w-full text-left border-collapse">
                                <thead>
                                    <tr className="border-b border-emerald-900/10 dark:border-[#1d3a34]">
                                        <th className="px-6 py-4 text-[10px] font-black tracking-widest text-slate-600 dark:text-slate-400 uppercase">Category Details</th>
                                        <th className="px-6 py-4 text-[10px] font-black tracking-widest text-slate-600 dark:text-slate-400 uppercase">Group</th>
                                        <th className="px-6 py-4 text-[10px] font-black tracking-widest text-slate-600 dark:text-slate-400 uppercase text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-200 dark:divide-slate-800">
                                    {categories.map((category) => (
                                        <tr key={category.id} className="hover:bg-emerald-50/50 dark:hover:bg-[#18342f]/70 transition-colors">
                                            <td className="px-6 py-5">
                                                <div className="text-sm font-bold text-slate-900 dark:text-white">{category.name}</div>
                                                <div className="text-[10px] font-mono text-slate-400 uppercase tracking-tighter">SLUG: {category.slug}</div>
                                            </td>
                                            <td className="px-6 py-5">
                                                {category.group ? (
                                                    <span className="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black tracking-widest bg-lime-500/10 text-teal-900 dark:text-lime-400 uppercase">
                                                        {category.group}
                                                    </span>
                                                ) : (
                                                    <span className="text-[10px] font-black text-slate-300 dark:text-slate-700 italic tracking-widest">NO GROUP</span>
                                                )}
                                            </td>
                                            <td className="px-6 py-5 text-right">
                                                <div className="flex items-center justify-end gap-2">
                                                    <button
                                                        onClick={() => handleEdit(category)}
                                                        className="p-2 bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white rounded-lg transition-all"
                                                        title="Edit"
                                                    >
                                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    </button>
                                                    <button
                                                        onClick={() => handleDelete(category)}
                                                        className="p-2 bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white rounded-lg transition-all"
                                                        title="Delete"
                                                    >
                                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                    {categories.length === 0 && (
                                        <tr>
                                            <td colSpan="3" className="px-6 py-12 text-center">
                                                <p className="text-sm text-slate-600 italic">No categories found. Create one to get started.</p>
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}




