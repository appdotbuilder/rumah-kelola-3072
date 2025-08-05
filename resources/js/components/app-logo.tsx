export default function AppLogo() {
    return (
        <>
            <div className="flex aspect-square size-8 items-center justify-center rounded-md bg-blue-600 text-white">
                <span className="text-lg font-bold">🏘️</span>
            </div>
            <div className="ml-1 grid flex-1 text-left text-sm">
                <span className="mb-0.5 truncate leading-tight font-semibold">SiRumah</span>
                <span className="truncate text-xs text-sidebar-foreground/70">Sistem Informasi Perumahan</span>
            </div>
        </>
    );
}