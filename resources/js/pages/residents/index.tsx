import { AppShell } from '@/components/app-shell';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Data Penghuni', href: '/residents' },
];

export default function ResidentsIndex() {
    return (
        <AppShell breadcrumbs={breadcrumbs}>
            <Head title="Data Penghuni" />
            
            <div className="space-y-6">
                {/* Header */}
                <div className="flex justify-between items-center">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">👥 Data Penghuni</h1>
                        <p className="text-gray-600 dark:text-gray-400">Kelola data penghuni perumahan</p>
                    </div>
                    <Link
                        href="/residents/create"
                        className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center space-x-2"
                    >
                        <span>➕</span>
                        <span>Tambah Penghuni</span>
                    </Link>
                </div>

                {/* Placeholder */}
                <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                    <div className="text-6xl mb-4">🚧</div>
                    <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        Halaman Data Penghuni
                    </h2>
                    <p className="text-gray-600 dark:text-gray-400 mb-6">
                        Fitur pengelolaan data penghuni sedang dalam pengembangan
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