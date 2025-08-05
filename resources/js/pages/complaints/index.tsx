import { AppShell } from '@/components/app-shell';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Keluhan', href: '/complaints' },
];

export default function ComplaintsIndex() {
    return (
        <AppShell breadcrumbs={breadcrumbs}>
            <Head title="Keluhan" />
            
            <div className="space-y-6">
                {/* Header */}
                <div className="flex justify-between items-center">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">🔧 Keluhan</h1>
                        <p className="text-gray-600 dark:text-gray-400">Kelola keluhan dan permintaan perbaikan</p>
                    </div>
                    <Link
                        href="/complaints/create"
                        className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center space-x-2"
                    >
                        <span>➕</span>
                        <span>Buat Keluhan</span>
                    </Link>
                </div>

                {/* Placeholder */}
                <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                    <div className="text-6xl mb-4">🚧</div>
                    <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        Halaman Keluhan
                    </h2>
                    <p className="text-gray-600 dark:text-gray-400 mb-6">
                        Fitur pengelolaan keluhan sedang dalam pengembangan
                    </p>
                    <Link
                        href="/dashboard"
                        className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg"
                    >
                        Kembali ke Dashboard
                    </Link>
                </div>
            </div>
        </AppShell>
    );
}