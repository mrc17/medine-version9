import AttemptLogin from '@/Components/AttemptLogin';
import Employe from '@/Components/Employe';
import Planification from '@/Components/Planification';
import RecentTickets from '@/Components/RecentTickets';
import StatCard from '@/Components/StatCard';
import TitrePage from '@/Components/TitrePage';
import Trajets from '@/Components/Trajets';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { useState } from 'react';

export default function Show({ auth, gare }) {
    const [activeSection, setActiveSection] = useState(null);
    const [error, setError] = useState(null);

    // Destructure gare properties for easier access
    const { trajets = [], planifications = [], caissiers = [], tickets = [], portefeuille = {}, nom } = gare;

    const totalEmployes = caissiers.length + (gare.comptable ? 1 : 0) + (gare.responsable_gare ? 1 : 0);

    const handleSectionClick = (section) => {
        setActiveSection(prev => (prev === section ? null : section));
    };

    if (error) return <div className="text-red-500 text-center py-5">{error}</div>;

    return (
        <AuthenticatedLayout
            user={auth}
            header={<TitrePage page={`Dashboard de ${nom}`} />}
        >
            <Head title={`Dashboard de ${nom}`} />

            <div className="py-5 md:pt-10 px-4">
                <div className="max-w-full mx-auto sm:px-6 lg:px-8">
                    {/* Statistiques */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <button onClick={() => handleSectionClick('trajets')}>
                            <StatCard title="Trajets" value={trajets.length} />
                        </button>
                        <button onClick={() => handleSectionClick('planifications')}>
                            <StatCard title="Planifications" value={planifications.length} />
                        </button>
                        <button onClick={() => handleSectionClick('employes')}>
                            <StatCard title="Employés" value={totalEmployes} />
                        </button>
                        <button onClick={() => handleSectionClick('tickets')}>
                            <StatCard title="Tickets" value={tickets.length} />
                        </button>
                        <button>
                            <StatCard
                                title="Montant ticket"
                                value={tickets.reduce((prev, ticket) => prev + parseInt(ticket.montant_ttc || 0, 10), 0)}
                            />
                        </button>
                    </div>

                    {/* Recent activities */}
                    <div className="mt-32 shadow-md md:mt-9">
                        {activeSection === 'tickets' && (
                            <RecentTickets tickets={gare.tickets} />
                        )}
                        {activeSection === 'trajets' && (
                            <Trajets trajets={trajets} />
                        )}
                        {activeSection === 'planifications' && (
                            <Planification planifications={planifications} />
                        )}
                        {activeSection === 'employes' && (
                            <Employe employes={[...caissiers, {user:gare.comptable,gare}, {user:gare.responsable_gare,gare}].filter(Boolean)} />
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
