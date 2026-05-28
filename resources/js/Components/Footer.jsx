import ApplicationLogo from '@/Components/ApplicationLogo';

export default function Footer() {
  return (
    <footer className="border-t border-emerald-900/10 px-6 py-16 dark:border-[#1d3a34] bg-white dark:bg-[#0b1715]">
      <div className="container mx-auto flex flex-col items-center justify-between gap-6 md:flex-row">
        <div className="flex items-center gap-2 opacity-60">
          <ApplicationLogo className="w-5 h-5" />
          <span className="text-sm font-semibold tracking-wide text-slate-900 dark:text-white">
            laradrug
          </span>
        </div>
        <p className="text-sm text-slate-600 dark:text-slate-400">
          &copy; {new Date().getFullYear()} laradrug. All rights reserved.
        </p>
      </div>
    </footer>
  );
}
