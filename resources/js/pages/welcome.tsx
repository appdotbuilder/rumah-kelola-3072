import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="SiRumah - Sistem Informasi Perumahan">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800">
                {/* Header */}
                <header className="w-full px-6 py-4">
                    <nav className="mx-auto flex max-w-7xl items-center justify-between">
                        <div className="flex items-center space-x-2">
                            <div className="rounded-lg bg-blue-600 p-2">
                                <span className="text-xl font-bold text-white">🏘️</span>
                            </div>
                            <span className="text-xl font-bold text-gray-900 dark:text-white">SiRumah</span>
                        </div>
                        <div className="flex items-center space-x-4">
                            {auth.user ? (
                                <Link
                                    href={route('dashboard')}
                                    className="rounded-lg bg-blue-600 px-6 py-2 text-white hover:bg-blue-700 transition-colors"
                                >
                                    Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={route('login')}
                                        className="text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400"
                                    >
                                        Masuk
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="rounded-lg bg-blue-600 px-6 py-2 text-white hover:bg-blue-700 transition-colors"
                                    >
                                        Daftar
                                    </Link>
                                </>
                            )}
                        </div>
                    </nav>
                </header>

                {/* Hero Section */}
                <main className="flex-1 px-6 py-12">
                    <div className="mx-auto max-w-7xl">
                        <div className="text-center mb-16">
                            <h1 className="text-5xl font-bold text-gray-900 dark:text-white mb-6">
                                🏘️ <span className="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">SiRumah</span>
                            </h1>
                            <p className="text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-3xl mx-auto">
                                Sistem Informasi Pengelolaan Data Rumah Perumahan dengan manajemen multi-level user yang lengkap dan profesional.
                            </p>
                            <div className="flex justify-center space-x-4">
                                {!auth.user && (
                                    <>
                                        <Link
                                            href={route('register')}
                                            className="bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition-colors"
                                        >
                                            Mulai Sekarang
                                        </Link>
                                        <Link
                                            href={route('login')}
                                            className="border-2 border-blue-600 text-blue-600 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-blue-50 transition-colors dark:hover:bg-gray-800"
                                        >
                                            Masuk ke Akun
                                        </Link>
                                    </>
                                )}
                                {auth.user && (
                                    <Link
                                        href={route('dashboard')}
                                        className="bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition-colors"
                                    >
                                        Ke Dashboard
                                    </Link>
                                )}
                            </div>
                        </div>

                        {/* Features Grid */}
                        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                            <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
                                <div className="text-3xl mb-4">🏠</div>
                                <h3 className="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                    Pengelolaan Rumah
                                </h3>
                                <p className="text-gray-600 dark:text-gray-300">
                                    Kelola data rumah dengan lengkap: alamat, tipe, luas, status, dan informasi pemilik/penghuni.
                                </p>
                            </div>

                            <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
                                <div className="text-3xl mb-4">👥</div>
                                <h3 className="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                    Manajemen Penghuni
                                </h3>
                                <p className="text-gray-600 dark:text-gray-300">
                                    Catat data penghuni, hubungan dengan rumah, dan riwayat tinggal secara terorganisir.
                                </p>
                            </div>

                            <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
                                <div className="text-3xl mb-4">💰</div>
                                <h3 className="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                    Pencatatan Pembayaran
                                </h3>
                                <p className="text-gray-600 dark:text-gray-300">
                                    Kelola pembayaran maintenance, utilitas, dan tagihan lainnya dengan sistem yang akurat.
                                </p>
                            </div>

                            <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
                                <div className="text-3xl mb-4">🔧</div>
                                <h3 className="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                    Keluhan & Perbaikan
                                </h3>
                                <p className="text-gray-600 dark:text-gray-300">
                                    Sistem ticketing untuk menangani keluhan dan permintaan perbaikan dari penghuni.
                                </p>
                            </div>
                        </div>

                        {/* User Roles Section */}
                        <div className="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-xl mb-16">
                            <h2 className="text-3xl font-bold text-center text-gray-900 dark:text-white mb-8">
                                👔 Multi-Level User Management
                            </h2>
                            <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div className="text-center">
                                    <div className="w-16 h-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span className="text-2xl">👨‍💼</span>
                                    </div>
                                    <h3 className="font-semibold text-gray-900 dark:text-white mb-2">Administrator</h3>
                                    <p className="text-sm text-gray-600 dark:text-gray-300">
                                        Akses penuh ke seluruh sistem dan pengelolaan user
                                    </p>
                                </div>

                                <div className="text-center">
                                    <div className="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span className="text-2xl">🏢</span>
                                    </div>
                                    <h3 className="font-semibold text-gray-900 dark:text-white mb-2">Manajer Perumahan</h3>
                                    <p className="text-sm text-gray-600 dark:text-gray-300">
                                        Mengelola operasional perumahan dan pembayaran
                                    </p>
                                </div>

                                <div className="text-center">
                                    <div className="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span className="text-2xl">💼</span>
                                    </div>
                                    <h3 className="font-semibold text-gray-900 dark:text-white mb-2">Staf Penjualan</h3>
                                    <p className="text-sm text-gray-600 dark:text-gray-300">
                                        Mengelola data rumah dan proses penjualan
                                    </p>
                                </div>

                                <div className="text-center">
                                    <div className="w-16 h-16 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span className="text-2xl">🏠</span>
                                    </div>
                                    <h3 className="font-semibold text-gray-900 dark:text-white mb-2">Penghuni</h3>
                                    <p className="text-sm text-gray-600 dark:text-gray-300">
                                        Akses untuk melihat tagihan dan mengajukan keluhan
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* App Screenshot/Demo Section */}
                        <div className="text-center mb-16">
                            <h2 className="text-3xl font-bold text-gray-900 dark:text-white mb-8">
                                📱 Interface yang Modern dan Intuitif
                            </h2>
                            <div className="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-xl max-w-4xl mx-auto">
                                <div className="bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-lg p-8 mb-6">
                                    <div className="grid grid-cols-3 gap-4 mb-4">
                                        <div className="bg-blue-200 dark:bg-blue-800 rounded h-20"></div>
                                        <div className="bg-green-200 dark:bg-green-800 rounded h-20"></div>
                                        <div className="bg-purple-200 dark:bg-purple-800 rounded h-20"></div>
                                    </div>
                                    <div className="space-y-2">
                                        <div className="bg-gray-300 dark:bg-gray-500 rounded h-3 w-3/4"></div>
                                        <div className="bg-gray-300 dark:bg-gray-500 rounded h-3 w-1/2"></div>
                                        <div className="bg-gray-300 dark:bg-gray-500 rounded h-3 w-2/3"></div>
                                    </div>
                                </div>
                                <p className="text-gray-600 dark:text-gray-300">
                                    Dashboard yang responsif dengan statistik real-time dan navigasi yang mudah digunakan
                                </p>
                            </div>
                        </div>

                        {/* CTA Section */}
                        <div className="text-center bg-blue-600 rounded-2xl p-12 text-white">
                            <h2 className="text-3xl font-bold mb-4">
                                🚀 Siap Untuk Mengelola Perumahan Anda?
                            </h2>
                            <p className="text-xl mb-8 text-blue-100">
                                Bergabunglah dengan sistem pengelolaan perumahan yang modern dan efisien
                            </p>
                            {!auth.user && (
                                <div className="flex justify-center space-x-4">
                                    <Link
                                        href={route('register')}
                                        className="bg-white text-blue-600 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-colors"
                                    >
                                        Daftar Gratis
                                    </Link>
                                    <Link
                                        href={route('login')}
                                        className="border-2 border-white text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition-colors"
                                    >
                                        Masuk Sekarang
                                    </Link>
                                </div>
                            )}
                            {auth.user && (
                                <Link
                                    href={route('dashboard')}
                                    className="bg-white text-blue-600 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-colors inline-block"
                                >
                                    Mulai Mengelola
                                </Link>
                            )}
                        </div>
                    </div>
                </main>

                {/* Footer */}
                <footer className="bg-gray-900 dark:bg-gray-950 text-white py-8">
                    <div className="mx-auto max-w-7xl px-6 text-center">
                        <div className="flex items-center justify-center space-x-2 mb-4">
                            <span className="text-2xl">🏘️</span>
                            <span className="text-xl font-bold">SiRumah</span>
                        </div>
                        <p className="text-gray-400 mb-4">
                            Sistem Informasi Pengelolaan Data Rumah Perumahan
                        </p>
                        <p className="text-gray-500 text-sm">
                            Dibangun dengan ❤️ menggunakan Laravel & React
                        </p>
                    </div>
                </footer>
            </div>
        </>
    );
}