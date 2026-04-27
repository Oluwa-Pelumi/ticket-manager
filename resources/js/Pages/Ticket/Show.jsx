import { useTheme } from '@/Contexts/ThemeContext';
import { useAlert } from '@/Contexts/AlertContext';
import FlashHandler from '@/Components/FlashHandler';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { useState } from 'react';

const subjects = [
    {value: 'insurance_claim', name: 'Insurance Claim'},
    {value: 'referral_request', name: 'Referral Request'},
    {value: 'file_a_complaint', name: 'File a Complaint'},
    {value: 'general_feedback', name: 'General Feedback'},
    {value: 'cancel_appointment', name: 'Cancel Appointment'},
    {value: 'request_lab_results', name: 'Request Lab Results'},
    {value: 'book_new_appointment', name: 'Book New Appointment'},
    {value: 'post_surgery_concern', name: 'Post-Surgery Concern'},
    {value: 'reschedule_appointment', name: 'Reschedule Appointment'},
    {value: 'request_medical_report', name: 'Request Medical Report'},
    {value: 'follow_up_consultation', name: 'Follow-up Consultation'},
    {value: 'urgent_medical_inquiry', name: 'Urgent Medical Inquiry'},
    {value: 'billing_payment_issue', name: 'Billing & Payment Issue'},
    {value: 'request_medical_records', name: 'Request Medical Records'},
    {value: 'request_prescription_refill', name: 'Request Prescription Refill'},
    {value: 'report_medication_side_effect', name: 'Report Medication Side Effect'},
];

export default function ShowTicket({ auth, ticket }) {
    const { theme, toggleTheme } = useTheme();
    const { showAlert } = useAlert();
    const { flash } = usePage().props;
    const [copiedId, setCopiedId] = useState(null);
    const [commentPreviewUrls, setCommentPreviewUrls] = useState([]);

    const commentForm = useForm({
        content: '',
        images: [],
    });

    const handleCopy = async (id) => {
        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(id);
            } else {
                const textArea = document.createElement("textarea");
                textArea.value = id;
                textArea.style.position = "fixed";
                textArea.style.left = "-9999px";
                textArea.style.top = "0";
                
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
            }

            setCopiedId(id);
            showAlert(`Ticket ID copied to clipboard!`, 'success');
            setTimeout(() => setCopiedId(null), 2000);
        } catch (err) {
            console.error('Failed to copy: ', err);
            showAlert('Failed to copy Ticket ID', 'error');
        }
    };

    const handleCommentSubmit = (e) => {
        e.preventDefault();
        commentForm.post(route('add-comment', { ticket: ticket.id }), {
            preserveScroll: true,
            onSuccess: () => {
                commentForm.reset();
                setCommentPreviewUrls([]);
            },
        });
    };

    const isTicketOwner = auth.user?.id === ticket.user_id || !ticket.user_id; // For guests, we assume if they have the link they own it
    const isAdmin = auth.user?.role === 'admin';

    return (
        <div className="relative min-h-screen flex flex-col bg-slate-50 selection:bg-[#FF2D20] selection:text-white dark:bg-slate-950 transition-colors duration-500 overflow-x-hidden">
            <Head title={`Ticket #${ticket.id.substring(0,8)}`} />

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
                <div className="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-[#FF2D20]/10 blur-[120px]" />
                <div className="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] rounded-full bg-blue-500/10 blur-[120px]" />
            </div>

            <nav className="relative z-10 w-full px-4 md:px-6 py-4 md:py-6 border-b border-slate-200 dark:border-slate-800 bg-white/50 dark:bg-slate-900/50 backdrop-blur-xl">
                <div className="max-w-5xl mx-auto flex justify-between items-center">
                    <Link href={route('home')} className="flex items-center space-x-3 group">
                        <div className="p-2 bg-[#FF2D20]/10 rounded-xl group-hover:bg-[#FF2D20] transition-colors duration-300">
                            <svg className="w-6 h-6 text-[#FF2D20] group-hover:text-white transition-colors duration-300" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M61.8548 14.6253C61.8778 14.7102 61.8895 14.7978 61.8897 14.8858V28.5615C61.8898 28.737 61.8434 28.9095 61.7554 29.0614C61.6675 29.2132 61.5409 29.3392 61.3887 29.4265L49.9104 36.0351V49.1337C49.9104 49.4902 49.7209 49.8192 49.4118 49.9987L25.4519 63.7916C25.3971 63.8227 25.3372 63.8427 25.2774 63.8639C25.255 63.8714 25.2338 63.8851 25.2101 63.8913C25.0426 63.9354 24.8666 63.9354 24.6991 63.8913C24.6716 63.8838 24.6467 63.8689 24.6205 63.8589C24.5657 63.8389 24.5084 63.8215 24.456 63.7916L0.501061 49.9987C0.348882 49.9113 0.222437 49.7853 0.134469 49.6334C0.0465019 49.4816 0.0465019 49.4816 0.000120578 49.3092 0 49.1337L0 8.10652C0 8.01678 0.0124642 7.92953 0.0348998 7.84477C0.0423783 7.8161 0.0598282 7.78993 0.0697995 7.76126C0.0884958 7.70891 0.105946 7.65531 0.133367 7.6067C0.152063 7.5743 0.179485 7.54812 0.20192 7.51821C0.230588 7.47832 0.256763 7.43719 0.290416 7.40229C0.319084 7.37362 0.356476 7.35243 0.388883 7.32751C0.425029 7.29759 0.457436 7.26518 0.498568 7.2415L12.4779 0.345059C12.6296 0.257786 12.8015 0.211853 12.9765 0.211853C13.1515 0.211853 13.3234 0.257786 13.475 0.345059L25.4531 7.2415H25.4556C25.4955 7.26643 25.5292 7.29759 25.5653 7.32626C25.5977 7.35119 25.6339 7.37362 25.6625 7.40104C25.6974 7.43719 25.7224 7.47832 25.7523 7.51821C25.7735 7.54812 25.8021 7.5743 25.8196 7.6067C25.8483 7.65656 25.8645 7.70891 25.8844 7.76126C25.8944 7.78993 25.9118 7.8161 25.9193 7.84602C25.9423 7.93096 25.954 8.01853 25.9542 8.10652V33.7317L35.9355 27.9844V14.8846C35.9355 14.7973 35.948 14.7088 35.9704 14.6253C35.9792 14.5954 35.9954 14.5692 36.0053 14.5405C36.0253 14.4882 36.0427 14.4346 36.0702 14.386C36.0888 14.3536 36.1163 14.3274 36.1375 14.2975C36.1674 14.2576 36.1923 14.2165 36.2272 14.1816C36.2559 14.1529 36.292 14.1317 36.3244 14.1068C36.3618 14.0769 36.3942 14.0445 36.4341 14.0208L48.4147 7.12434C48.5663 7.03694 48.7383 6.99094 48.9133 6.99094C49.0883 6.99094 49.2602 7.03694 49.4118 7.12434L61.3899 14.0208C61.4323 14.0457 61.4647 14.0769 61.5021 14.1055C61.5333 14.1305 61.5694 14.1529 61.5981 14.1803C61.633 14.2165 61.6579 14.2576 61.6878 14.2975C61.7103 14.3274 61.7377 14.3536 61.7551 14.386C61.7838 14.4346 61.8 14.4882 61.8199 14.5405C61.8312 14.5692 61.8474 14.5954 61.8548 14.6253ZM59.893 27.9844V16.6121L55.7013 19.0252L49.9104 22.3593V33.7317L59.8942 27.9844H59.893ZM47.9149 48.5566V37.1768L42.2187 40.4299L25.953 49.7133V61.2003L47.9149 48.5566ZM1.99677 9.83281V48.5566L23.9562 61.199V49.7145L12.4841 43.2219L12.4804 43.2194L12.4754 43.2169C12.4368 43.1945 12.4044 43.1621 12.3682 43.1347C12.3371 43.1097 12.3009 43.0898 12.2735 43.0624L12.271 43.0586C12.2386 43.0275 12.2162 42.9888 12.1887 42.9539C12.1638 42.9203 12.1339 42.8916 12.114 42.8567L12.1127 42.853C12.0903 42.8156 12.0766 42.7707 12.0604 42.7283C12.0442 42.6909 12.023 42.656 12.013 42.6161C12.0005 42.5688 11.998 42.5177 11.9931 42.4691C11.9881 42.4317 11.9781 42.3943 11.9781 42.3569V15.5801L6.18848 12.2446L1.99677 9.83281ZM12.9777 2.36177L2.99764 8.10652L12.9752 13.8513L22.9541 8.10527L12.9752 2.36177H12.9777ZM18.1678 38.2138L23.9574 34.8809V9.83281L19.7657 12.2459L13.9749 15.5801V40.6281L18.1678 38.2138ZM48.9133 9.14105L38.9344 14.8858L48.9133 20.6305L58.8909 14.8846L48.9133 9.14105ZM47.9149 22.3593L42.124 19.0252L37.9323 16.6121V27.9844L43.7219 31.3174L47.9149 33.7317V22.3593ZM24.9533 47.987L39.59 39.631L46.9065 35.4555L36.9352 29.7145L25.4544 36.3242L14.9907 42.3482L24.9533 47.987Z" fill="currentColor"/></svg>
                        </div>
                        <span className="text-xl font-bold text-slate-900 dark:text-white group-hover:text-[#FF2D20] transition-colors">{import.meta.env.VITE_APP_NAME || 'MedTick'}</span>
                    </Link>
                </div>
            </nav>

            <main className="relative z-10 flex-grow py-12 px-6">
                <div className="max-w-5xl mx-auto space-y-8">
                    <div className="flex items-center justify-between">
                        <Link href={route('check-status')} className="inline-flex items-center text-sm font-bold text-slate-500 hover:text-[#FF2D20] transition-colors">
                            <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                            Back to Status Search
                        </Link>
                        <span className={`inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold uppercase shadow-sm ${
                            ticket.status === 'open' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' :
                            ticket.status === 'in-progress' ? 'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400 border border-orange-200 dark:border-orange-800' :
                            'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700'
                        }`}>
                            {ticket.status.replace('-', ' ')}
                        </span>
                    </div>

                    <FlashHandler />

                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        {/* Left Column: Details */}
                        <div className="space-y-8">
                            <div>
                                <h4 className="text-2xl font-bold text-slate-900 dark:text-white mb-6 flex items-center">
                                    <svg className="w-6 h-6 mr-3 text-[#FF2D20]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Ticket Details
                                </h4>

                                <div className="p-6 md:p-8 rounded-[2.5rem] bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border border-slate-200 dark:border-slate-800 shadow-2xl">
                                    <div className="text-[10px] font-black text-[#FF2D20] mb-2 uppercase tracking-[0.2em]">Ticket ID</div>
                                    <div className="flex items-center gap-3 mb-6 group/id">
                                        <div className="text-base md:text-xl text-slate-900 dark:text-white font-bold break-all">{ticket.id}</div>
                                        <button
                                            onClick={(e) => { e.stopPropagation(); handleCopy(ticket.id); }}
                                            className="flex items-center gap-2 px-2 md:px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-[#FF2D20] transition-all border border-transparent hover:border-[#FF2D20]/20"
                                            title="Copy ID"
                                        >
                                            {copiedId === ticket.id ? (
                                                <svg className="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"/></svg>
                                            ) : (
                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                            )}
                                        </button>
                                    </div>

                                    <div className="text-[10px] font-black text-[#FF2D20] mb-2 uppercase tracking-[0.2em]">Subject</div>
                                    <div className="text-lg md:text-xl text-slate-900 dark:text-white font-bold mb-6">{subjects.find(s => s.value == ticket.subject)?.name || ticket.subject}</div>

                                    <div className="text-[10px] font-black text-[#FF2D20] mb-2 uppercase tracking-[0.2em]">Priority</div>
                                    <div className="mb-6">
                                        <span className={`inline-flex items-center space-x-1 px-3 py-1 rounded-full text-[10px] md:text-xs font-black uppercase tracking-wider ${
                                            ticket.priority === 'high' ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' :
                                            ticket.priority === 'medium' ? 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' :
                                            'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400'
                                        }`}>
                                            {ticket.priority}
                                        </span>
                                    </div>

                                    <div className="text-[10px] font-black text-[#FF2D20] mb-2 uppercase tracking-[0.2em]">Description</div>
                                    <div className="text-slate-600 dark:text-slate-400 whitespace-pre-wrap leading-relaxed text-[13px] md:text-sm">{ticket.content}</div>
                                </div>
                            </div>

                            {(ticket.images?.length > 0 || ticket.filename) && (
                                <div>
                                    <h4 className="text-sm font-bold text-slate-900 dark:text-white mb-4 uppercase tracking-widest flex items-center">
                                        <svg className="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        Attachments
                                    </h4>
                                    <div className="flex flex-wrap gap-4">
                                        {ticket.filename && (
                                            <a href={`/storage/${ticket.filename}`} target="_blank" className="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-slate-800 shadow-md">
                                                <img src={`/storage/${ticket.filename}`} className="w-full h-full object-cover transition-transform group-hover/img:scale-110" />
                                                <div className="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                    <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </div>
                                            </a>
                                        )}
                                        {ticket.images?.map((img, i) => (
                                            <a key={i} href={`/storage/${img}`} target="_blank" className="group/img relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-white dark:border-slate-800 shadow-md">
                                                <img src={`/storage/${img}`} className="w-full h-full object-cover transition-transform group-hover/img:scale-110" />
                                                <div className="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                                    <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </div>
                                            </a>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Right Column: Conversation */}
                        <div className="space-y-8">
                            <div>
                                <h4 className="text-2xl font-bold text-slate-900 dark:text-white mb-6 flex items-center">
                                    <svg className="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                    Conversation
                                </h4>

                                <div className="space-y-4 max-h-[400px] md:max-h-[500px] overflow-y-auto pr-1 md:pr-2 custom-scrollbar mb-6 p-4 md:p-6 rounded-[2.5rem] bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border border-slate-200 dark:border-slate-800 shadow-2xl">
                                    {ticket.comments?.length > 0 ? ticket.comments.map((comment, ci) => (
                                        <div key={ci} className={`flex flex-col ${comment.user_id === ticket.user_id || (!comment.user_id && !ticket.user_id) ? 'items-end' : 'items-start'}`}>
                                            <div className={`max-w-[90%] md:max-w-[85%] p-3 md:p-4 rounded-3xl ${comment.user_id === ticket.user_id || (!comment.user_id && !ticket.user_id) ? 'bg-[#FF2D20] text-white rounded-br-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white rounded-bl-sm'}`}>
                                                <div className="flex items-center space-x-2 mb-2">
                                                    <span className="text-[9px] md:text-[10px] font-black uppercase opacity-70">{comment.user?.name || 'Guest'}</span>
                                                    <span className="text-[9px] md:text-[10px] opacity-50">{new Date(comment.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                                                </div>
                                                <div className="text-[13px] md:text-sm whitespace-pre-wrap">{comment.content}</div>
                                                {comment.images?.length > 0 && (
                                                    <div className="flex flex-wrap gap-2 mt-3">
                                                        {comment.images.map((cimg, cii) => (
                                                            <a key={cii} href={`/storage/${cimg}`} target="_blank" className="w-12 h-12 md:w-16 md:h-16 rounded-xl overflow-hidden border border-white/20">
                                                                <img src={`/storage/${cimg}`} className="w-full h-full object-cover" />
                                                            </a>
                                                        ))}
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    )) : (
                                        <div className="text-center py-12 opacity-40">
                                            <svg className="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                            <div className="italic text-sm">No comments yet. Start the conversation.</div>
                                        </div>
                                    )}
                                </div>

                                {(isTicketOwner || isAdmin) && ticket.status !== 'closed' && (
                                    <form onSubmit={handleCommentSubmit} className="space-y-4">
                                        <div className="relative group/comment">
                                            <textarea
                                                value={commentForm.data.content}
                                                onChange={e => commentForm.setData('content', e.target.value)}
                                                placeholder="Type your message..."
                                                rows="4"
                                                className="w-full px-4 md:px-6 py-4 md:py-5 rounded-[2rem] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-[#FF2D20] outline-none transition-all resize-none shadow-xl text-sm md:text-base"
                                                required
                                            ></textarea>

                                            <div className="absolute bottom-4 right-4 flex items-center space-x-2">
                                                <input
                                                    type="file"
                                                    id="comment-images"
                                                    className="hidden"
                                                    multiple
                                                    accept="image/*"
                                                    onChange={(e) => {
                                                        const files = Array.from(e.target.files);
                                                        commentForm.setData('images', files);
                                                        setCommentPreviewUrls(files.map(f => URL.createObjectURL(f)));
                                                    }}
                                                />
                                                <label htmlFor="comment-images" className="p-2 md:p-3 text-slate-400 hover:text-[#FF2D20] hover:bg-[#FF2D20]/10 rounded-2xl cursor-pointer transition-all bg-slate-50 dark:bg-slate-800">
                                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </label>
                                                <button
                                                    type="submit"
                                                    disabled={commentForm.processing || !commentForm.data.content.trim()}
                                                    className="p-2 md:p-3 bg-[#FF2D20] text-white rounded-2xl shadow-lg shadow-[#FF2D20]/20 hover:scale-110 active:scale-95 transition-all disabled:opacity-50 disabled:hover:scale-100"
                                                >
                                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                                </button>
                                            </div>
                                        </div>

                                        {commentPreviewUrls.length > 0 && (
                                            <div className="flex flex-wrap gap-3 animate-in fade-in slide-in-from-bottom-2 duration-300">
                                                {commentPreviewUrls.map((url, i) => (
                                                    <div key={i} className="relative group/cp">
                                                        <img src={url} className="w-16 h-16 rounded-2xl object-cover border-2 border-white dark:border-slate-800 shadow-md" />
                                                        <button
                                                            type="button"
                                                            onClick={() => {
                                                                const nf = [...commentForm.data.images]; nf.splice(i, 1); commentForm.setData('images', nf);
                                                                const nu = [...commentPreviewUrls]; nu.splice(i, 1); setCommentPreviewUrls(nu);
                                                            }}
                                                            className="absolute -top-2 -right-2 bg-rose-500 text-white p-1 rounded-full opacity-0 group-hover/cp:opacity-100 transition-opacity shadow-lg hover:scale-110"
                                                        >
                                                            <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        </button>
                                                    </div>
                                                ))}
                                            </div>
                                        )}
                                    </form>
                                )}
                                {ticket.status === 'closed' && (
                                    <div className="p-4 bg-slate-100 dark:bg-slate-800/50 rounded-2xl text-center text-sm font-medium text-slate-500 border border-slate-200 dark:border-slate-700">
                                        This ticket is closed. No further comments can be added.
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    );
}
