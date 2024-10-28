import { Head } from '@inertiajs/react';
import StatCard from '@/Components/StatCard';
import TitrePage from './../Components/TitrePage';
import RecentTickets from '@/Components/RecentTickets';
import DashboardCharts from '@/Components/DashboardCharts';
import StatCardProgress from '@/Components/StatCardProgress';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Dashboard({ auth, stats }) {
    console.log(stats); // Debugging to ensure the data is correct

    return (
        <AuthenticatedLayout
            user={auth}
            header={<TitrePage page="Tableau de Bord" />}
        >
            <Head title="Tableau de Bord" />
            <div className="py-5 px-4 md:pt-10">
                <div className="max-w-full mx-auto sm:px-6 lg:px-8">
                    {/* Statistics Section */}
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <StatCardProgress
                            title="Total Utilisateurs"
                            value={stats.users}
                            label="Utilisateurs inscrits ce mois-ci"
                        />
                        <StatCardProgress
                            title="Total Tickets Vendus"
                            value={stats.tickets}
                            label="Tickets vendus ce mois-ci"
                        />
                        <StatCardProgress
                            title="Total Compagnies"
                            value={stats.compagnies}
                            label="Compagnies inscrites ce mois-ci"
                        />
                        <StatCard
                            title="Total Pays"
                            percentage={(stats.pays * 196) / 100}
                            value={stats.pays}
                        />
                    </div>

                    {/* Charts Section */}
                    <div className="shadow-md ">
                        <DashboardCharts tickets={stats.tickets} users={stats.users} />
                    </div>

                    {/* Recent Tickets Section */}
                    <div className="mt-10 pt-6  md:pt-0 shadow-md">
                        <RecentTickets tickets={stats.tickets} />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
