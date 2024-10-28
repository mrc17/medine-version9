import { Head } from '@inertiajs/react';
import StatCard from '@/Components/StatCard';
import TitrePage from '@/Components/TitrePage';
import { useState, useEffect, useMemo } from 'react';
import ModalVoirTicket from '@/Components/ModalVoirTicket';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Index({ auth, affiliation }) {
    const [openDropdown, setOpenDropdown] = useState({});
    const [searchTerm, setSearchTerm] = useState('');
    const [currentPage, setCurrentPage] = useState(1);
    const itemsPerPage = 8;
    const [timeRange, setTimeRange] = useState('mois');
    const [isOpenModalVoir, setIsOpenModalVoir] = useState(false);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedTicket, setSelectedTicket] = useState(null);

    // Filter and Paginate Affiliations
    const filteredAffiliations = useMemo(() => {
        return affiliation.compagnie.tickets.filter((ticket) =>
            ticket.user.nom.toLowerCase().includes(searchTerm.toLowerCase()) ||
            ticket.user.prenom.toLowerCase().includes(searchTerm.toLowerCase())
        );
    }, [affiliation, searchTerm]);

    const totalPages = Math.ceil(filteredAffiliations.length / itemsPerPage);
    const currentAffiliations = useMemo(() => {
        const startIndex = (currentPage - 1) * itemsPerPage;
        return filteredAffiliations.slice(startIndex, startIndex + itemsPerPage);
    }, [filteredAffiliations, currentPage, itemsPerPage]);

    // Calculate Total Commission
    const calculateCommission = (ticket) => {
        const commission = ticket.user.role.nom === "caissier/caissière"
            ? (parseInt(ticket.montant_ttc) - parseInt(ticket.tarif)) / 3
            : (parseInt(ticket.montant_ttc) - parseInt(ticket.tarif)) / 6;
        return isNaN(commission) ? 0 : commission;
    };

    const calculateTotalCommission = useMemo(() => {
        return filteredAffiliations.reduce((total, ticket) => total + calculateCommission(ticket), 0).toFixed(2);
    }, [filteredAffiliations]);

    const getFilteredCommissionsByTimeRange = () => {
        const now = new Date();
        return filteredAffiliations.filter(ticket => {
            const ticketDate = new Date(ticket.created_at);
            switch (timeRange) {
                case 'jour':
                    return ticketDate >= new Date(now.getFullYear(), now.getMonth(), now.getDate());
                case 'semaine':
                    return ticketDate >= new Date(now.getFullYear(), now.getMonth(), now.getDate() - now.getDay());
                case 'mois':
                    return ticketDate >= new Date(now.getFullYear(), now.getMonth(), 1);
                case '3 mois':
                    return ticketDate >= new Date(now.getFullYear(), now.getMonth() - 3, 1);
                case '6 mois':
                    return ticketDate >= new Date(now.getFullYear(), now.getMonth() - 6, 1);
                case '12 mois':
                    return ticketDate >= new Date(now.getFullYear(), now.getMonth() - 12, 1);
                default:
                    return true;
            }
        });
    };

    const calculateTimeRangeCommission = useMemo(() => {
        const filteredCommissions = getFilteredCommissionsByTimeRange();
        return filteredCommissions.reduce((total, ticket) => total + calculateCommission(ticket), 0).toFixed(2);
    }, [timeRange, filteredAffiliations]);

    const toggleDropdown = (id) => {
        setOpenDropdown(prev => ({
            ...Object.keys(prev).reduce((acc, key) => {
                acc[key] = key === id.toString() ? !prev[key] : false;
                return acc;
            }, {}),
            [id]: !prev[id],
        }));
    };

    const handlePageChange = (newPage) => {
        if (newPage < 1 || newPage > totalPages) return;
        setCurrentPage(newPage);
    };

    const openTicketModal = (ticket) => {
        setSelectedTicket(ticket);
        setIsModalOpen(true);
    };

    const DropdownMenu = ({ ticket }) => (
        <div className="absolute z-50 w-44 -bottom-15 right-4 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
            <ul className="py-1 text-sm text-gray-700 dark:text-gray-200">
                <li>
                    <button
                        onClick={() => {
                            setIsOpenModalVoir(true);
                            openTicketModal(ticket);
                        }}
                        className="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                        Détails
                    </button>
                </li>
            </ul>
        </div>
    );

    return (
        <AuthenticatedLayout
            user={auth}
            header={<TitrePage page={`Détails des informations sur le client ${affiliation.user.nom} ${affiliation.user.prenom}`} />}
        >
            <Head title="Dashboard" />

            <div className="py-5">
                <div className="w-full mx-auto sm:px-6 lg:px-8">
                    <ModalVoirTicket show={isModalOpen} onClose={() => setIsModalOpen(false)} ticket={selectedTicket} />
                    <section className="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
                        <div className="mx-auto max-w-screen-xl px-4 lg:px-12">
                            <div className="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                                <div className="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                                    <div className="w-full md:w-1/2">
                                        <form className="flex items-center">
                                            <label htmlFor="simple-search" className="sr-only">Search</label>
                                            <div className="relative w-full">
                                                <input
                                                    type="text"
                                                    id="simple-search"
                                                    className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                    placeholder="Search"
                                                    value={searchTerm}
                                                    onChange={(e) => setSearchTerm(e.target.value)}
                                                    required
                                                />
                                            </div>
                                        </form>
                                    </div>
                                    <div className="md:w-1/2">
                                        <select
                                            value={timeRange}
                                            onChange={(e) => setTimeRange(e.target.value)}
                                            className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            <option value="jour">Aujourd'hui</option>
                                            <option value="semaine">Cette semaine</option>
                                            <option value="mois">Ce mois</option>
                                            <option value="3 mois">3 derniers mois</option>
                                            <option value="6 mois">6 derniers mois</option>
                                            <option value="12 mois">12 derniers mois</option>
                                        </select>
                                    </div>
                                </div>

                                <div className="grid grid-cols-1 my-2 p-2 md:grid-cols-2 lg:grid-cols-2 gap-4">
                                    <StatCard title="Commission Totale" value={calculateTotalCommission} />
                                    <StatCard title={`Commission pour ${timeRange}`} value={calculateTimeRangeCommission} />
                                </div>

                                <div className="overflow-x-auto">
                                    <table className="min-w-full">
                                        <thead className="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th scope="col" className="py-3 px-6 text-xs font-medium text-gray-700 uppercase tracking-wider dark:text-gray-400">Clients</th>
                                                <th scope="col" className="py-3 px-6 text-xs font-medium text-gray-700 uppercase tracking-wider dark:text-gray-400">Montant TTC</th>
                                                <th scope="col" className="py-3 px-6 text-xs font-medium text-gray-700 uppercase tracking-wider dark:text-gray-400">Tarif</th>
                                                <th scope="col" className="py-3 px-6 text-xs font-medium text-gray-700 uppercase tracking-wider dark:text-gray-400">Commission</th>
                                                <th scope="col" className="py-3 px-6 text-xs font-medium text-gray-700 uppercase tracking-wider dark:text-gray-400">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody className="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                            {currentAffiliations.map((ticket, index) => (
                                                <tr key={ticket.id} className="hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <td className="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{`${ticket.user.nom} ${ticket.user.prenom}`}</td>
                                                    <td className="py-4 px-6 text-sm text-gray-900 whitespace-nowrap dark:text-white">{ticket.montant_ttc}</td>
                                                    <td className="py-4 px-6 text-sm text-gray-900 whitespace-nowrap dark:text-white">{ticket.tarif}</td>
                                                    <td className="py-4 px-6 text-sm text-gray-900 whitespace-nowrap dark:text-white">{calculateCommission(ticket)}</td>
                                                    <td className="relative py-4 px-6">
                                                        <button
                                                            onClick={() => toggleDropdown(ticket.id)}
                                                            className="inline-flex justify-center items-center p-2 text-sm font-medium text-center text-gray-500 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                                                            <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fillRule="evenodd" d="M10 3a1 1 0 110 2 1 1 0 010-2zM10 9a1 1 0 110 2 1 1 0 010-2zM10 15a1 1 0 110 2 1 1 0 010-2z" clipRule="evenodd" />
                                                            </svg>
                                                        </button>
                                                        {openDropdown[ticket.id] && <DropdownMenu ticket={ticket} />}
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>

                                <div className="flex justify-between items-center mt-4">
                                    <button onClick={() => handlePageChange(currentPage - 1)} disabled={currentPage === 1} className="px-4 py-2 bg-gray-500 text-white rounded-md disabled:opacity-50">Précédent</button>
                                    <span>{`Page ${currentPage} de ${totalPages}`}</span>
                                    <button onClick={() => handlePageChange(currentPage + 1)} disabled={currentPage === totalPages} className="px-4 py-2 bg-gray-500 text-white rounded-md disabled:opacity-50">Suivant</button>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
