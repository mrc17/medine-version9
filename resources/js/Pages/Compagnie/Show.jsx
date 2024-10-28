import AttemptLogin from '@/Components/AttemptLogin';
import Employe from '@/Components/Employe';
import Portefeuille from '@/Components/Portefeuille';
import RecentCars from '@/Components/RecentCars';
import RecentGares from '@/Components/RecentGares';
import RecentTickets from '@/Components/RecentTickets';
import Responsable from '@/Components/Responsable';
import StatCard from '@/Components/StatCard';
import TitrePage from '@/Components/TitrePage';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { useState, useEffect } from 'react';

export default function Show({ auth, compagnie }) {
    console.log(compagnie);

    // État pour afficher la section active
    const [activeSection, setActiveSection] = useState(null);

    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    // Effet pour simuler le chargement des données
    useEffect(() => {
        const fetchData = async () => {
            try {
                setLoading(true);
                // Simuler une API ici
                // const response = await fetch('/api/compagnie');
                // const data = await response.json();
                // setCompagnie(data);
                setLoading(false);
            } catch (err) {
                setError('Impossible de charger les données. Veuillez réessayer plus tard.');
                setLoading(false);
            }
        };

        fetchData();
    }, []);

    const handleSectionClick = (section) => {
        // Si la section est déjà active, la désélectionner, sinon sélectionner celle-ci
        setActiveSection(prev => (prev === section ? null : section));
    };

    if (loading) return <div className="text-center py-5">Chargement...</div>;
    if (error) return <div className="text-red-500 text-center py-5">{error}</div>;

    return (
        <AuthenticatedLayout
            user={auth}
            header={<TitrePage page={`Dashboard de ${compagnie.nom}`} />}
        >
            <Head title="Dashboard" />

            <div className="py-5 md:pt-10 px-4">
                <div className="max-w-full mx-auto sm:px-6 lg:px-8">
                    {/* Section des statistiques */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <button onClick={() => handleSectionClick('gares')}>
                            <StatCard title="Gares" value={compagnie.gares.length} />
                        </button>
                        <button onClick={() => handleSectionClick('cars')}>
                            <StatCard title="Cars" value={compagnie.cars.length} />
                        </button>
                        <button onClick={() => handleSectionClick('employes')}>
                            <StatCard title="Employés" value={compagnie.employes.length} />
                        </button>
                        <button onClick={() => handleSectionClick('tickets')}>
                            <StatCard title="Tickets achetés" value={compagnie.tickets.length} />
                        </button>
                        <button onClick={() => handleSectionClick('portefeuille')}>
                            <StatCard title="Portefeuille" value={parseInt(compagnie.portefeuille.montant_ticket) + parseInt(compagnie.portefeuille.commission)} />
                        </button>
                    </div>

                    {/* Section des commandes récentes */}
                    <Responsable user={compagnie.responsable} />
                    <div className="mt-32 shadow-md md:mt-9">
                        {activeSection === 'tickets' && (
                            <RecentTickets tickets={compagnie.tickets.sort((a, b) => b.id - a.id)} key="recent-tickets" />
                        )}
                        {activeSection === 'cars' && (
                            <RecentCars cars={compagnie.cars.sort((a, b) => b.id - a.id)} key="recent-cars" />
                        )}
                        {activeSection === 'gares' && (
                            <RecentGares gares={compagnie.gares.sort((a, b) => b.id - a.id)} key="recent-gares" />
                        )}
                        {activeSection === 'employes' && (
                            <Employe employes={compagnie.employes.sort((a, b) => b.id - a.id)} key="recent-employes" />
                        )}
                        {activeSection === 'portefeuille' && (
                            <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">
                                <Portefeuille title="Commission" value={compagnie.portefeuille.commission} />
                                <Portefeuille title="Montant des tickets" value={compagnie.portefeuille.montant_ticket} />
                                <AttemptLogin title="Nombre de tentatives" value={compagnie.portefeuille.attempt_logins} />
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
