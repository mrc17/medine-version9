import { Head } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Line } from 'react-chartjs-2';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
} from 'chart.js';
import { useState } from 'react';
import ModalRetrait from '@/Components/ModalRetrait';
import TitrePage from '@/Components/TitrePage';

// Enregistrement des composants nécessaires de chart.js
ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
);

export default function Index({ auth,  transactions = [] }) {
    const [openModal, setOpenModal] = useState(false);

    // Calcul dynamique des mois pour les labels
    const currentMonth = new Date().getMonth();
    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    const labels = monthNames.slice(0, currentMonth + 1); // Display months until the current one

    const data = {
        labels: labels,
        datasets: [
            {
                label: 'Revenus',
                data: [1200, 1900, 3000, 5000, 2000, 3000, 4000], // Exemple de données
                fill: false,
                backgroundColor: 'rgb(75, 192, 192)',
                borderColor: 'rgba(75, 192, 192, 0.2)',
            },
            {
                label: 'Dépenses',
                data: [1000, 1700, 2500, 4500, 1500, 2500, 3500], // Exemple de données
                fill: false,
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgba(255, 99, 132, 0.2)',
            },
        ],
    };

    return (
        <AuthenticatedLayout
            user={auth}
            header={<TitrePage page="Portefeuille" />}
        >
            <Head title="Portefeuille du Restaurant" />
            <ModalRetrait openModal={openModal} setOpenModal={setOpenModal} />
            <div className="py-12 px-2">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                    <div className="dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <h2 className="text-2xl font-semibold text-gray-800">Solde</h2>
                        <div className="font-bold text-center text-5xl sm:text-6xl lg:text-7xl mt-4">
                            5585888 Franc CFA
                        </div>
                        <button onClick={() => { setOpenModal(true) }} className="w-full bg-green-500 text-white rounded-lg p-3 mt-6 hover:bg-green-600 transition">
                            Retrait
                        </button>
                    </div>

                    <div className="dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <h3 className="text-xl font-semibold text-gray-800">Statistiques Financières</h3>
                        <div className="mt-4 h-72 w-full">
                            <Line data={data} />
                        </div>
                    </div>

                    <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
                        <div className="dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                            <h3 className="text-xl font-semibold text-gray-800">Informations</h3>
                            <p className="mt-4"><strong>Nom du Restaurant:</strong> 55</p>
                            <p className="mt-2"><strong>Adresse:</strong> 55</p>
                            <p className="mt-2"><strong>Gérant:</strong> 88</p>
                        </div>
                        <div className="dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                            <h3 className="text-xl font-semibold text-gray-800">Transactions Récentes</h3>
                            <ul className="space-y-2 mt-4">
                                {transactions.length > 0 ? (
                                    transactions.map((transaction, index) => (
                                        <li key={index} className="flex justify-between border-b pb-2">
                                            <span>{new Date(transaction.date).toLocaleDateString()}</span>  {/* Better date formatting */}
                                            <span>{transaction.amount} €</span>
                                        </li>
                                    ))
                                ) : (
                                    <li className="text-gray-500">Aucune transaction récente</li>
                                )}
                            </ul>
                        </div>
                    </div>

                    <div className="dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <h3 className="text-xl font-semibold text-gray-800">Actions Rapides</h3>
                        <div className="flex flex-col sm:flex-row sm:space-x-4 mt-4 space-y-4 sm:space-y-0">
                            <button className="bg-blue-500 text-white rounded-lg px-4 py-2 hover:bg-blue-600 transition">
                                Transfert Rapide
                            </button>
                            <button className="bg-yellow-500 text-white rounded-lg px-4 py-2 hover:bg-yellow-600 transition">
                                Générer Rapport
                            </button>
                            <button className="bg-red-500 text-white rounded-lg px-4 py-2 hover:bg-red-600 transition">
                                Contacter Support
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
