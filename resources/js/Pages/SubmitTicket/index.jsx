import { useState } from 'react';
import { useTheme } from '@/Contexts/ThemeContext';
import FlashHandler from '@/Components/FlashHandler';
import { Head, Link, useForm } from '@inertiajs/react';

const subjects =
    {
        'Appointments': [
            {value: 'cancel_appointment', name: 'Cancel Appointment'},
            {value: 'book_new_appointment', name: 'Book New Appointment'},
            {value: 'reschedule_appointment', name: 'Reschedule Appointment'},
        ],
        'Medical': [
            {value: 'referral_request', name: 'Referral Request'},
            {value: 'request_lab_results', name: 'Request Lab Results'},
            {value: 'request_medical_report', name: 'Request Medical Report'},
            {value: 'request_prescription_refill', name: 'Request Prescription Refill'},
        ],
        'Consultation': [
            {value: 'post_surgery_concern', name: 'Post-Surgery Concern'},
            {value: 'follow_up_consultation', name: 'Follow-up Consultation'},
            {value: 'urgent_medical_inquiry', name: 'Urgent Medical Inquiry'},
        ],
        'Billing & Admin': [
            {value: 'insurance_claim', name: 'Insurance Claim'},
            {value: 'billing_payment_issue', name: 'Billing & Payment Issue'},
            {value: 'request_medical_records', name: 'Request Medical Records'},
        ],
        'Feedback': [
            {value: 'file_a_complaint', name: 'File a Complaint'},
            {value: 'general_feedback', name: 'General Feedback'},
            {value: 'report_medication_side_effect', name: 'Report Medication Side Effect'},
        ],

    }

export default function SubmitTicket({ auth }) {
    const { theme, toggleTheme }                             = useTheme();
    const [previewUrls, setPreviewUrls]                      = useState([]);
    const { data, setData, post, processing, errors, reset } = useForm({
        images         : [],
        subject        : '',
        content        : '',
        priority       : 'low',
        name           : auth.user?.name || '',
        email          : auth.user?.email || '',
        whatsapp_number: auth.user?.whatsapp_number || '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('submit-ticket'), {
            onSuccess: () => reset(),
        });
    };

    return (
        <>
            <Head title="Submit Ticket" />

            <div className="relative min-h-screen flex flex-col items-center justify-center bg-slate-50 selection:bg-[#FF2D20] selection:text-white dark:bg-slate-950 overflow-x-hidden transition-colors duration-500 py-20 px-6">

                {/* Theme Switcher FAB */}
                <button
                    onClick={toggleTheme}
                    className="fixed bottom-8 right-8 md:top-8 md:bottom-auto z-50 p-3 rounded-2xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border border-slate-200 dark:border-slate-800 shadow-xl hover:scale-110 active:scale-95 transition-all duration-300 group"
                    aria-label="Toggle Theme"
                >
                    {theme === 'dark' ? (
                        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 text-yellow-400 fill-current" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                        </svg>
                    ) : (
                        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 text-slate-700 fill-current" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M20.354 15.354A9 9 0 118.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    )}
                </button>

                {/* Background Aesthetics */}
                <div className="absolute inset-0 bg-white dark:bg-slate-950 pointer-events-none transition-colors duration-500">
                    <div className="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-[#FF2D20]/20 blur-[120px] animate-pulse" />
                    <div className="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] rounded-full bg-blue-500/20 blur-[120px]" />
                    <img
                        id="background"
                        className="absolute inset-0 w-full h-full object-cover opacity-10 dark:opacity-20 invert dark:invert-0 pointer-events-none transition-all duration-500"
                        src="https://laravel.com/assets/img/welcome/background.svg"
                        alt=""
                    />
                </div>

                <div className="relative z-10 w-full max-w-3xl">
                    <div className="text-center mb-10">
                        <Link href={route('home')} className="inline-flex items-center text-xs md:text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-[#FF2D20] dark:hover:text-[#FF2D20] transition-colors mb-4">
                            <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Home
                        </Link>
                        <h1 className="text-3xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-5xl">
                            Submit a <span className="text-[#FF2D20]">Ticket</span>
                        </h1>
                        <p className="mt-4 text-sm md:text-lg text-slate-600 dark:text-slate-400 px-4">
                            Provide the details below and we'll help as soon as possible.
                        </p>
                    </div>

                    <FlashHandler />

                    <form onSubmit={submit} className="group relative block p-8 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border border-slate-200 dark:border-slate-800 shadow-2xl space-y-6">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {/* Name */}
                            <div className="space-y-2">
                                <label className="text-sm font-semibold text-slate-900 dark:text-white" htmlFor="name">Full Name</label>
                                <input
                                    id="name"
                                    type="text"
                                    value={data.name}
                                    onChange={e => setData('name', e.target.value)}
                                    className="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-[#FF2D20] transition-all outline-none"
                                    placeholder="John Doe"
                                    required
                                />
                                {errors.name && <div className="text-red-500 text-xs mt-1">{errors.name}</div>}
                            </div>

                            {/* Email */}
                            <div className="space-y-2">
                                <label className="text-sm font-semibold text-slate-900 dark:text-white" htmlFor="email">Email Address</label>
                                <input
                                    id="email"
                                    type="email"
                                    value={data.email}
                                    onChange={e => setData('email', e.target.value)}
                                    className="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-[#FF2D20] transition-all outline-none"
                                    placeholder="john@example.com"
                                    required
                                />
                                {errors.email && <div className="text-red-500 text-xs mt-1">{errors.email}</div>}
                            </div>

                            {/* WhatsApp */}
                            <div className="space-y-2">
                                <label className="text-sm font-semibold text-slate-900 dark:text-white" htmlFor="whatsapp">WhatsApp Number</label>
                                <input
                                    id="whatsapp"
                                    type="tel"
                                    value={data.whatsapp_number}
                                    onChange={e => setData('whatsapp_number', e.target.value)}
                                    className="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-[#FF2D20] transition-all outline-none"
                                    placeholder="+234..."
                                />
                                {errors.whatsapp_number && <div className="text-red-500 text-xs mt-1">{errors.whatsapp_number}</div>}
                            </div>

                            {/* Priority */}
                            <div className="space-y-2">
                                <label className="text-sm font-semibold text-slate-900 dark:text-white" htmlFor="priority">Priority Level</label>
                                <select
                                    id="priority"
                                    value={data.priority}
                                    onChange={e => setData('priority', e.target.value)}
                                    className="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-[#FF2D20] transition-all outline-none appearance-none"
                                >
                                    <option value="low">⬇️ Low Priority</option>
                                    <option value="medium">⚡ Medium Priority</option>
                                    <option value="high">🚩 High Priority</option>
                                </select>
                            </div>
                        </div>

                        {/* Subject */}
                        <div className="space-y-2">
                            <label className="text-sm font-semibold text-slate-900 dark:text-white" htmlFor="subject">Subject</label>
                            <select
                                id="subject"
                                value={data.subject}
                                onChange={e => setData('subject', e.target.value)}
                                className="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-[#FF2D20] transition-all outline-none appearance-none"
                            >
                                <option value="" disabled>Select Subject</option>
                                {Object.entries(subjects).map(([groupLabel, items]) => (
                                    <optgroup key={groupLabel} label={groupLabel}>
                                        {items.map((sub, idx) => (
                                            <option key={idx} value={sub.value}>{sub.name}</option>
                                        ))}
                                    </optgroup>
                                ))}
                            </select>
                        </div>

                        {/* Content */}
                        <div className="space-y-2">
                            <label className="text-sm font-semibold text-slate-900 dark:text-white" htmlFor="content">Description of Issue</label>
                            <textarea
                                id="content"
                                value={data.content}
                                onChange={e => setData('content', e.target.value)}
                                rows="5"
                                className="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-[#FF2D20] transition-all outline-none resize-none"
                                placeholder="Please describe your problem in detail..."
                                required
                            ></textarea>
                            {errors.content && <div className="text-red-500 text-xs mt-1">{errors.content}</div>}
                        </div>

                        {/* Image Upload */}
                        <div className="space-y-4">
                            <label className="text-sm font-semibold text-slate-900 dark:text-white block">Attachments (Images)</label>
                            <div className="relative group/upload">
                                <input
                                    type="file"
                                    onChange={(e) => {
                                        const files = Array.from(e.target.files);
                                        setData('images', files);
                                        const urls = files.map(file => URL.createObjectURL(file));
                                        setPreviewUrls(urls);
                                    }}
                                    className="hidden"
                                    id="image-upload"
                                    accept="image/*"
                                    multiple
                                />
                                <label
                                    htmlFor="image-upload"
                                    className="flex flex-col items-center justify-center border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-2xl p-6 md:p-8 hover:border-[#FF2D20] dark:hover:border-[#FF2D20] hover:bg-[#FF2D20]/5 transition-all cursor-pointer group"
                                >
                                    <svg className="w-8 h-8 md:w-10 md:h-10 text-slate-400 group-hover:text-[#FF2D20] transition-colors mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <span className="text-slate-600 dark:text-slate-400 font-medium text-xs md:text-sm text-center">Click to upload multiple or drag and drop</span>
                                    <span className="text-slate-400 text-[10px] mt-1">Maximum file size: 5MB per image</span>
                                </label>
                            </div>

                            {/* Previews */}
                            {previewUrls.length > 0 && (
                                <div className="mt-4 animate-in fade-in zoom-in duration-300">
                                    <div className="text-xs font-semibold text-slate-500 mb-3 uppercase tracking-wider">Image Previews ({previewUrls.length}):</div>
                                    <div className="flex flex-wrap gap-3 md:gap-4">
                                        {previewUrls.map((url, idx) => (
                                            <div key={idx} className="relative group/preview">
                                                <img
                                                    src={url}
                                                    className="w-24 h-24 md:w-32 md:h-32 object-cover rounded-2xl border-4 border-white dark:border-slate-800 shadow-xl transition-transform group-hover/preview:scale-105"
                                                    alt={`Preview ${idx + 1}`}
                                                />
                                                <button
                                                    type="button"
                                                    onClick={() => {
                                                        const newFiles = [...data.images];
                                                        newFiles.splice(idx, 1);
                                                        setData('images', newFiles);

                                                        const newUrls = [...previewUrls];
                                                        newUrls.splice(idx, 1);
                                                        setPreviewUrls(newUrls);
                                                    }}
                                                    className="absolute -top-2 -right-2 bg-red-500 text-white p-1.5 rounded-full shadow-lg hover:bg-red-600 transition-colors opacity-0 group-hover/preview:opacity-100"
                                                >
                                                    <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Submit */}
                        <div className="pt-4">
                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full py-4 rounded-2xl bg-[#FF2D20] text-white font-bold text-lg shadow-xl shadow-[#FF2D20]/20 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:hover:scale-100 transition-all"
                            >
                                {processing ? 'Submitting...' : 'Submit Ticket'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </>
    );
}
