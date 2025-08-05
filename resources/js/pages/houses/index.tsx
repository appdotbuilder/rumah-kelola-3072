import { AppShell } from '@/components/app-shell';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

interface House {
    id: number;
    block_number: string;
    address: string;
    house_type: string;
    land_area: number;
    building_area: number;
    status: string;
    owner_name: string | null;
    owner_phone: string | null;
    bedrooms: number;
    bathrooms: number;
    selling_price: number | null;
    formatted_selling_price: string;
    status_color: string;
    active_resident?: {
        name: string;
        phone: string;
    } | null;
}

interface HouseStats {
    total: number;
    available: number;
    sold: number;
    reserved: number;
    maintenance: number;
}

interface Props {
    houses: {
        data: House[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    filters: {
        status?: string;
        house_type?: string;
        search?: string;
    };
    stats: HouseStats;
    [key: string]: unknown;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Data Rumah', href: '/houses' },
];

export default function HousesIndex({ houses, stats }: Props) {
    const getStatusBadge = (status: string) => {
        const statusConfig = {
            available: { label: 'Tersedia', color: 'green', icon: '✅' },
            sold: { label: 'Terjual', color: 'blue', icon: '🏠' },
            reserved: { label: 'Dipesan', color: 'yellow', icon: '⏳' },
            maintenance: { label: 'Maintenance', color: 'red', icon: '🔧' },
        };

        const config = statusConfig[status as keyof typeof statusConfig] || statusConfig.available;
        
        return (
            <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-${config.color}-100 text-${config.color}-800`}>
                {config.icon} {config.label}
            </span>
        );
    };

    return (
        <AppShell breadcrumbs={breadcrumbs}>
            <Head title="Data Rumah" />
            
            <div className="space-y-6">
                {/* Header */}
                <div className="flex justify-between items-center">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">🏘️ Data Rumah</h1>
                        <p className="text-gray-600 dark:text-gray-400">Kelola data rumah di perumahan</p>
                    </div>
                    <Link
                        href="/houses/create"
                        className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center space-x-2"
                    >
                        <span>➕</span>
                        <span>Tambah Rumah</span>
                    </Link>
                </div>

                {/* Stats */}
                <div className="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div className="bg-white dark:bg-gray-800 rounded-lg p-4 text-center">
                        <div className="text-2xl font-bold text-gray-900 dark:text-white">{stats.total}</div>
                        <div className="text-sm text-gray-600 dark:text-gray-400">Total Rumah</div>
                    </div>
                    <div className="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 text-center">
                        <div className="text-2xl font-bold text-green-600">{stats.available}</div>
                        <div className="text-sm text-green-600">Tersedia</div>
                    </div>
                    <div className="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 text-center">
                        <div className="text-2xl font-bold text-blue-600">{stats.sold}</div>
                        <div className="text-sm text-blue-600">Terjual</div>
                    </div>
                    <div className="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 text-center">
                        <div className="text-2xl font-bold text-yellow-600">{stats.reserved}</div>
                        <div className="text-sm text-yellow-600">Dipesan</div>
                    </div>
                    <div className="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 text-center">
                        <div className="text-2xl font-bold text-red-600">{stats.maintenance}</div>
                        <div className="text-sm text-red-600">Maintenance</div>
                    </div>
                </div>

                {/* Houses Grid */}
                <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <div className="p-6">
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {houses.data.map((house) => (
                                <div key={house.id} className="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div className="flex justify-between items-start mb-3">
                                        <div>
                                            <h3 className="font-semibold text-gray-900 dark:text-white">
                                                🏠 {house.block_number}
                                            </h3>
                                            <p className="text-sm text-gray-600 dark:text-gray-400">
                                                {house.house_type}
                                            </p>
                                        </div>
                                        {getStatusBadge(house.status)}
                                    </div>
                                    
                                    <div className="space-y-2 mb-4">
                                        <div className="text-sm">
                                            <span className="text-gray-600 dark:text-gray-400">📍 Alamat:</span>
                                            <p className="text-gray-900 dark:text-white">{house.address}</p>
                                        </div>
                                        
                                        <div className="flex justify-between text-sm">
                                            <span>
                                                <span className="text-gray-600 dark:text-gray-400">🏡 Luas:</span>
                                                <span className="text-gray-900 dark:text-white ml-1">
                                                    {house.land_area}m² / {house.building_area}m²
                                                </span>
                                            </span>
                                        </div>
                                        
                                        <div className="flex justify-between text-sm">
                                            <span>
                                                <span className="text-gray-600 dark:text-gray-400">🛏️ Kamar:</span>
                                                <span className="text-gray-900 dark:text-white ml-1">
                                                    {house.bedrooms} KT, {house.bathrooms} KM
                                                </span>
                                            </span>
                                        </div>

                                        {house.selling_price && (
                                            <div className="text-sm">
                                                <span className="text-gray-600 dark:text-gray-400">💰 Harga:</span>
                                                <span className="text-gray-900 dark:text-white font-semibold ml-1">
                                                    {house.formatted_selling_price}
                                                </span>
                                            </div>
                                        )}

                                        {house.owner_name && (
                                            <div className="text-sm">
                                                <span className="text-gray-600 dark:text-gray-400">👤 Pemilik:</span>
                                                <span className="text-gray-900 dark:text-white ml-1">
                                                    {house.owner_name}
                                                </span>
                                            </div>
                                        )}
                                    </div>
                                    
                                    <div className="flex space-x-2">
                                        <Link
                                            href={`/houses/${house.id}`}
                                            className="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-3 rounded text-sm"
                                        >
                                            Detail
                                        </Link>
                                        <Link
                                            href={`/houses/${house.id}/edit`}
                                            className="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-center py-2 px-3 rounded text-sm"
                                        >
                                            Edit
                                        </Link>
                                    </div>
                                </div>
                            ))}
                        </div>

                        {houses.data.length === 0 && (
                            <div className="text-center py-12">
                                <div className="text-4xl mb-4">🏠</div>
                                <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                    Belum ada data rumah
                                </h3>
                                <p className="text-gray-600 dark:text-gray-400 mb-4">
                                    Mulai dengan menambahkan rumah pertama
                                </p>
                                <Link
                                    href="/houses/create"
                                    className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg inline-flex items-center space-x-2"
                                >
                                    <span>➕</span>
                                    <span>Tambah Rumah</span>
                                </Link>
                            </div>
                        )}

                        {/* Pagination */}
                        {houses.last_page > 1 && (
                            <div className="mt-6 flex justify-center">
                                <div className="flex space-x-2">
                                    {Array.from({ length: houses.last_page }, (_, i) => i + 1).map((page) => (
                                        <Link
                                            key={page}
                                            href={`/houses?page=${page}`}
                                            className={`px-3 py-2 rounded ${
                                                page === houses.current_page
                                                    ? 'bg-blue-600 text-white'
                                                    : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                                            }`}
                                        >
                                            {page}
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AppShell>
    );
}