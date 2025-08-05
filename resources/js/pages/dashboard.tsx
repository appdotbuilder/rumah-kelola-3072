import { AppShell } from '@/components/app-shell';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

interface DashboardStats {
    houses?: {
        total: number;
        available: number;
        sold: number;
        reserved: number;
    };
    residents?: {
        total: number;
        active: number;
    };
    payments?: {
        total: number;
        pending: number;
        overdue: number;
        paid_this_month: number;
    };
    complaints?: {
        total: number;
        open: number;
        high_priority: number;
        resolved_this_month: number;
    };
    my_payments?: {
        pending: number;
        overdue: number;
    };
    my_complaints?: {
        open: number;
        total: number;
    };
}

interface RecentActivity {
    recent_houses?: Array<Record<string, unknown>>;
    recent_payments?: Array<Record<string, unknown>>;
    recent_complaints?: Array<Record<string, unknown>>;
    my_payments?: Array<Record<string, unknown>>;
    my_complaints?: Array<Record<string, unknown>>;
}

interface Props {
    stats: DashboardStats;
    recentActivity: RecentActivity;
    userRole: string;
    [key: string]: unknown;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

export default function Dashboard({ stats, userRole }: Props) {
    const { auth } = usePage<SharedData>().props;

    const getRoleTitle = (role: string) => {
        switch (role) {
            case 'administrator':
                return 'Administrator';
            case 'housing_manager':
                return 'Manajer Perumahan';
            case 'sales_staff':
                return 'Staf Penjualan';
            case 'resident':
                return 'Penghuni';
            default:
                return 'User';
        }
    };

    const getRoleIcon = (role: string) => {
        switch (role) {
            case 'administrator':
                return '👨‍💼';
            case 'housing_manager':
                return '🏢';
            case 'sales_staff':
                return '💼';
            case 'resident':
                return '🏠';
            default:
                return '👤';
        }
    };

    return (
        <AppShell breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            
            <div className="space-y-6">
                {/* Welcome Section */}
                <div className="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg p-6 text-white">
                    <div className="flex items-center justify-between">
                        <div>
                            <h1 className="text-2xl font-bold">
                                {getRoleIcon(userRole)} Selamat datang, {auth.user.name}!
                            </h1>
                            <p className="text-blue-100 mt-1">
                                {getRoleTitle(userRole)} - SiRumah Dashboard
                            </p>
                        </div>
                        <div className="text-right">
                            <div className="text-blue-100 text-sm">
                                {new Date().toLocaleDateString('id-ID', {
                                    weekday: 'long',
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                })}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {/* Houses Stats (Admin, Manager, Sales) */}
                    {stats.houses && (
                        <>
                            <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Total Rumah</p>
                                        <p className="text-2xl font-bold text-gray-900 dark:text-white">{stats.houses.total}</p>
                                    </div>
                                    <div className="text-3xl">🏘️</div>
                                </div>
                                <div className="mt-4 flex space-x-4 text-xs">
                                    <span className="text-green-600">✅ {stats.houses.available} Tersedia</span>
                                    <span className="text-blue-600">🏠 {stats.houses.sold} Terjual</span>
                                </div>
                                <Link 
                                    href="/houses" 
                                    className="mt-4 text-blue-600 hover:text-blue-700 text-sm font-medium inline-block"
                                >
                                    Lihat Semua →
                                </Link>
                            </div>

                            <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Penghuni</p>
                                        <p className="text-2xl font-bold text-gray-900 dark:text-white">{stats.residents?.total || 0}</p>
                                    </div>
                                    <div className="text-3xl">👥</div>
                                </div>
                                <div className="mt-4 text-xs text-green-600">
                                    ✅ {stats.residents?.active || 0} Aktif
                                </div>
                                <Link 
                                    href="/residents" 
                                    className="mt-4 text-blue-600 hover:text-blue-700 text-sm font-medium inline-block"
                                >
                                    Lihat Semua →
                                </Link>
                            </div>
                        </>
                    )}

                    {/* Payments Stats (Admin, Manager) */}
                    {stats.payments && (
                        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Pembayaran</p>
                                    <p className="text-2xl font-bold text-gray-900 dark:text-white">{stats.payments.total}</p>
                                </div>
                                <div className="text-3xl">💰</div>
                            </div>
                            <div className="mt-4 flex space-x-4 text-xs">
                                <span className="text-yellow-600">⏳ {stats.payments.pending} Pending</span>
                                <span className="text-red-600">⚠️ {stats.payments.overdue} Terlambat</span>
                            </div>
                            <Link 
                                href="/payments" 
                                className="mt-4 text-blue-600 hover:text-blue-700 text-sm font-medium inline-block"
                            >
                                Kelola Pembayaran →
                            </Link>
                        </div>
                    )}

                    {/* Complaints Stats (Admin, Manager) */}
                    {stats.complaints && (
                        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Keluhan</p>
                                    <p className="text-2xl font-bold text-gray-900 dark:text-white">{stats.complaints.total}</p>
                                </div>
                                <div className="text-3xl">🔧</div>
                            </div>
                            <div className="mt-4 flex space-x-4 text-xs">
                                <span className="text-red-600">🚨 {stats.complaints.open} Terbuka</span>
                                <span className="text-orange-600">⚡ {stats.complaints.high_priority} Prioritas Tinggi</span>
                            </div>
                            <Link 
                                href="/complaints" 
                                className="mt-4 text-blue-600 hover:text-blue-700 text-sm font-medium inline-block"
                            >
                                Kelola Keluhan →
                            </Link>
                        </div>
                    )}

                    {/* Resident-specific Stats */}
                    {stats.my_payments && (
                        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Tagihan Saya</p>
                                    <p className="text-2xl font-bold text-gray-900 dark:text-white">
                                        {stats.my_payments.pending + stats.my_payments.overdue}
                                    </p>
                                </div>
                                <div className="text-3xl">💳</div>
                            </div>
                            <div className="mt-4 text-xs">
                                {stats.my_payments.overdue > 0 ? (
                                    <span className="text-red-600">⚠️ {stats.my_payments.overdue} Terlambat</span>
                                ) : (
                                    <span className="text-green-600">✅ Tidak ada tagihan terlambat</span>
                                )}
                            </div>
                            <Link 
                                href="/payments" 
                                className="mt-4 text-blue-600 hover:text-blue-700 text-sm font-medium inline-block"
                            >
                                Lihat Tagihan →
                            </Link>
                        </div>
                    )}

                    {stats.my_complaints && (
                        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Keluhan Saya</p>
                                    <p className="text-2xl font-bold text-gray-900 dark:text-white">{stats.my_complaints.total}</p>
                                </div>
                                <div className="text-3xl">📝</div>
                            </div>
                            <div className="mt-4 text-xs">
                                <span className="text-yellow-600">🔄 {stats.my_complaints.open} Sedang Diproses</span>
                            </div>
                            <div className="flex space-x-2 mt-4">
                                <Link 
                                    href="/complaints" 
                                    className="text-blue-600 hover:text-blue-700 text-sm font-medium"
                                >
                                    Lihat Keluhan →
                                </Link>
                                <Link 
                                    href="/complaints/create" 
                                    className="text-green-600 hover:text-green-700 text-sm font-medium"
                                >
                                    Buat Keluhan
                                </Link>
                            </div>
                        </div>
                    )}
                </div>

                {/* Quick Actions */}
                <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                    <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">🚀 Aksi Cepat</h2>
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                        {(userRole === 'administrator' || userRole === 'housing_manager' || userRole === 'sales_staff') && (
                            <>
                                <Link 
                                    href="/houses/create"
                                    className="flex flex-col items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors"
                                >
                                    <div className="text-2xl mb-2">🏠</div>
                                    <span className="text-sm font-medium text-gray-900 dark:text-white">Tambah Rumah</span>
                                </Link>
                                <Link 
                                    href="/residents/create"
                                    className="flex flex-col items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors"
                                >
                                    <div className="text-2xl mb-2">👤</div>
                                    <span className="text-sm font-medium text-gray-900 dark:text-white">Tambah Penghuni</span>
                                </Link>
                            </>
                        )}
                        
                        {(userRole === 'administrator' || userRole === 'housing_manager') && (
                            <Link 
                                href="/payments/create"
                                className="flex flex-col items-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-colors"
                            >
                                <div className="text-2xl mb-2">💰</div>
                                <span className="text-sm font-medium text-gray-900 dark:text-white">Buat Tagihan</span>
                            </Link>
                        )}
                        
                        <Link 
                            href="/complaints/create"
                            className="flex flex-col items-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors"
                        >
                            <div className="text-2xl mb-2">📋</div>
                            <span className="text-sm font-medium text-gray-900 dark:text-white">Buat Keluhan</span>
                        </Link>
                    </div>
                </div>

                {/* Recent Activity - Will be implemented later */}
                <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                    <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">📊 Aktivitas Terbaru</h2>
                    <div className="text-center text-gray-500 dark:text-gray-400 py-8">
                        <div className="text-4xl mb-2">🚧</div>
                        <p>Fitur aktivitas terbaru akan segera hadir!</p>
                    </div>
                </div>
            </div>
        </AppShell>
    );
}