import { useState } from 'react';
import { useAlert } from '@/Contexts/AlertContext';
import FlashHandler from '@/Components/FlashHandler';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import ApplicationLogo from '@/Components/ApplicationLogo';

const subjects = [
    {value: 'refill_request', name: 'Refill request'},
    {value: 'missed_dose', name: 'Missed dose — guidance needed'},
    {value: 'wrong_medication', name: 'Wrong medication dispensed'},
    {value: 'allergic_reaction', name: 'Suspected allergic reaction'},
    {value: 'wrong_dosage', name: 'Wrong dosage or strength on label'},
    {value: 'side_effects', name: 'Experiencing unexpected side effects'},
    {value: 'speak_with_pharmacist', name: 'Request to speak with a pharmacist'},
    {value: 'running_out', name: 'Medication running out before next appointment'},
    {value: 'prescription_expired', name: 'Prescription expired or needs renewal'},
    {value: 'drug_interactions', name: 'Drug interactions with food or supplements'},
    {value: 'storage_handling_questions', name: 'Questions about storage or handling'},
    {value: 'drug_interaction', name: 'Drug interaction concern (with another medication)'},
    {value: 'transfer_prescription', name: 'Transfer of prescription from another facility'},
    {value: 'unclear_instructions', name: 'Unclear instructions on how to take the medication'},
    {value: 'difficulty_using_drug_form', name: 'Difficulty using the drug form (inhaler, injection, patch)'},
];

export default function ShowTicket({ auth, ticket }) {
    const { showAlert }                               = useAlert();
    const [copiedId, setCopiedId]                     = useState(null);
    const [commentPreviewUrls, setCommentPreviewUrls] = useState([]);

    const commentForm = useForm({
        content: '',
        images : [],
    });

    const handleCopy = async (id) => {
        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(id);
            } else {
                const textArea                = document.createElement("textarea");
                      textArea.value          = id;
                      textArea.style.position = "fixed";
                      textArea.style.left     = "-9999px";
                      textArea.style.top      = "0";

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

    const isTicketOwner = auth.user?.id === ticket.user_id || !ticket.user_id;
    const isAdmin       = auth.user?.role === 'admin';

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between w-full">
                    <div className="flex items-center gap-4">
                        <div className="w-12 h-12 rounded-2xl bg-teal-900 flex items-center justify-center shadow-lg border border-white/20">
                            <svg className="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2.5}>
                                <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path strokeLinecap="round" strokeLinejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <div className="flex flex-col">
                            <h2 className="text-xl font-black text-slate-900 dark:text-white tracking-tight">Ticket</h2>
                            <span className="text-[10px] font-black tracking-[0.3em] text-slate-400">Details</span>
                        </div>
                    </div>
                    <span className={`inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black tracking-widest shadow-sm ${
                        ticket.status === 'open' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' :
                        ticket.status === 'in-progress' ? 'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400 border border-orange-200 dark:border-orange-800' :
                        'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700'
                    }`}>
                        {ticket.status.replace('-', ' ')}
                    </span>
                </div>
            }
        >
            <Head title={`Ticket #${ticket.id.substring(0,8)}`} />

            <div className="max-w-7xl mx-auto py-12 px-6 space-y-8">

                <div className="flex items-center justify-between">
                    <Link href={route('check-status')} className="inline-flex items-center text-sm font-bold text-slate-500 hover:text-teal-900 dark:hover:text-lime-400 transition-colors">
                        <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        Back to Status Search
                    </Link>
                </div>

                <FlashHandler />

                <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    {/* Left Column: Details */}
                    <div className="space-y-8">
                        <div>
                            <h4 className="text-sm font-black text-slate-900 dark:text-white mb-6 flex items-center tracking-[0.2em]">
                                <svg className="w-5 h-5 mr-3 text-teal-900 dark:text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                               Specifications
                            </h4>

                            <div className="p-6 md:p-8 rounded-[2.5rem] bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border border-slate-200 dark:border-slate-800 shadow-2xl">
                                <div className="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-2 tracking-[0.2em]">Control Reference</div>
                                <div className="flex items-center gap-3 mb-8 group/id">
                                    <div className="text-xl md:text-2xl text-slate-900 dark:text-white font-black tracking-tight break-all">{ticket.id}</div>
                                    <button
                                        onClick={(e) => { e.stopPropagation(); handleCopy(ticket.id); }}
                                        className="flex items-center gap-2 px-2 md:px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-teal-900 dark:hover:text-lime-400 transition-all border border-transparent hover:border-teal-900/20"
                                        title="Copy ID"
                                    >
                                        {copiedId === ticket.id ? (
                                            <svg className="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"/></svg>
                                        ) : (
                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                        )}
                                    </button>
                                </div>

                                <div className="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-2 tracking-[0.2em]">Subject</div>
                                <div className="text-lg md:text-xl text-slate-900 dark:text-white font-bold mb-6">{subjects.find(s => s.value == ticket.subject)?.name || ticket.subject}</div>

                                <div className="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-2 tracking-[0.2em]">Priority</div>
                                <div className="mb-6">
                                    <span className={`inline-flex items-center space-x-1 px-3 py-1 rounded-full text-[10px] md:text-xs font-black tracking-wider ${
                                        ticket.priority === 'high' ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' :
                                        ticket.priority === 'medium' ? 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' :
                                        'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400'
                                    }`}>
                                        {ticket.priority}
                                    </span>
                                </div>

                                <div className="text-[10px] font-black text-teal-900 dark:text-lime-400 mb-2 tracking-[0.2em]">Issue Specification</div>
                                <div className="text-slate-600 dark:text-slate-400 whitespace-pre-wrap leading-relaxed text-[13px] md:text-sm">{ticket.content}</div>
                            </div>
                        </div>

                        {(ticket.images?.length > 0 || ticket.filename) && (
                            <div>
                                <h4 className="text-sm font-bold text-slate-900 dark:text-white mb-4 tracking-widest flex items-center">
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
                            <h4 className="text-sm font-black text-slate-900 dark:text-white mb-6 flex items-center tracking-[0.2em]">
                                <svg className="w-5 h-5 mr-3 text-teal-900 dark:text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                Conversation
                            </h4>

                            <div className="space-y-4 max-h-[400px] md:max-h-[500px] overflow-y-auto pr-1 md:pr-2 custom-scrollbar mb-6 p-4 md:p-6 rounded-[2.5rem] bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border border-slate-200 dark:border-slate-800 shadow-2xl">
                                {ticket.comments?.length > 0 ? ticket.comments.map((comment, ci) => (
                                    <div key={ci} className={`flex flex-col ${comment.user_id === ticket.user_id || (!comment.user_id && !ticket.user_id) ? 'items-end' : 'items-start'}`}>
                                        <div className={`max-w-[90%] md:max-w-[85%] p-4 md:p-6 rounded-[2rem] ${comment.user_id === ticket.user_id || (!comment.user_id && !ticket.user_id) ? 'bg-teal-900 text-white rounded-br-sm shadow-xl' : 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-bl-sm border border-slate-200/50 dark:border-slate-700/50 shadow-sm'}`}>
                                            <div className="flex items-center space-x-2 mb-2">
                                                <span className="text-[9px] md:text-[10px] font-black opacity-70">{comment.user?.name || 'Guest'}</span>
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
                                            className="w-full px-6 py-5 rounded-[2.5rem] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-lime-500 outline-none transition-all resize-none shadow-xl text-sm md:text-base"
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
                                            <label htmlFor="comment-images" className="p-3 text-slate-400 hover:text-teal-900 dark:hover:text-lime-400 hover:bg-lime-500/10 rounded-2xl cursor-pointer transition-all bg-slate-50 dark:bg-slate-800">
                                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </label>
                                            <button
                                                type="submit"
                                                disabled={commentForm.processing || !commentForm.data.content.trim()}
                                                className="p-3 bg-teal-900 text-white rounded-2xl shadow-xl hover:bg-lime-500 hover:text-teal-900 hover:scale-110 active:scale-95 transition-all disabled:opacity-50 disabled:hover:scale-100"
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

            {/* Footer */}
            <footer className="relative z-10 px-6 py-10 mt-12 border-t border-slate-200/60 dark:border-slate-800/60">
                <div className="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
                    <div className="flex items-center gap-2 opacity-60">
                        <ApplicationLogo className="w-5 h-5" />
                        <span className="text-sm font-semibold tracking-wide text-slate-900 dark:text-white">laradrug</span>
                    </div>
                    <p className="text-sm text-slate-500 dark:text-slate-500">
                        &copy; {new Date().getFullYear()} laradrug. All rights reserved.
                    </p>

                </div>
            </footer>
        </AuthenticatedLayout>
    );
}
