import React, { useState } from 'react';

const RecentTickets = ({ tickets }) => {
    const [currentPage, setCurrentPage] = useState(1);
    const [searchQuery, setSearchQuery] = useState('');
    const [sortCriteria, setSortCriteria] = useState('num_ticket'); // Tri par défaut
    const ticketsPerPage = 20;

    // Filtrer les tickets en fonction de la requête de recherche
    const filterTickets = (tickets) => {
        return tickets.filter(ticket => {
            const ticketNum = ticket.num_ticket?.toString().toLowerCase() || '';
            const userName = ticket.user ? `${ticket.user.nom || ''} ${ticket.user.prenom || ''}`.toLowerCase() : '';
            return ticketNum.includes(searchQuery.toLowerCase()) || userName.includes(searchQuery.toLowerCase());
        });
    };

    // Trier les tickets selon les critères sélectionnés
    const sortTickets = (tickets) => {
        const sortOrder = {
            "Confirmeé": 1,
            "En attente": 2,
            "Scanné": 3,
            "Annulé": 4,
        };

        return [...tickets].sort((a, b) => {
            if (sortCriteria === 'num_ticket') {
                return (a.num_ticket || 0) - (b.num_ticket || 0);
            } else if (sortCriteria === 'montant_ttc') {
                return (a.montant_ttc || 0) - (b.montant_ttc || 0);
            } else if (sortCriteria === 'status') {
                return (sortOrder[a.status] || 5) - (sortOrder[b.status] || 5);
            }
            return 0; // Aucun tri par défaut
        });
    };

    const filteredTickets = filterTickets(tickets);
    const sortedTickets = sortTickets(filteredTickets);

    // Calculer le nombre total de pages
    const totalPages = Math.ceil(sortedTickets.length / ticketsPerPage);

    // Obtenir les tickets à afficher pour la page actuelle
    const currentTickets = sortedTickets.slice((currentPage - 1) * ticketsPerPage, currentPage * ticketsPerPage);

    const handleNextPage = () => {
        if (currentPage < totalPages) {
            setCurrentPage(prev => prev + 1);
        }
    };

    const handlePrevPage = () => {
        if (currentPage > 1) {
            setCurrentPage(prev => prev - 1);
        }
    };

    const handleSortChange = (e) => {
        setSortCriteria(e.target.value);
        setCurrentPage(1); // Réinitialiser à la première page lors du changement de tri
    };

    return (
        <section className="bg-white py-8 antialiased rounded-xl dark:bg-gray-800 md:py-10">
            <div className="mx-auto max-w-screen-xl px-4 2xl:px-0">
                <div className="mx-auto max-w-5xl">
                    <div className="gap-4 sm:flex sm:items-center sm:justify-between">
                        <h2 className="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">
                            Dernières Commandes
                        </h2>
                        <div className="mt-4 sm:mt-0">
                            <input
                                type="text"
                                placeholder="Recherche..."
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                className="px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                            />
                        </div>
                        <div className="mt-4 sm:mt-0 sm:ml-4">
                            <select onChange={handleSortChange} className="px-4 py-2 border rounded-md dark:bg-gray-800 dark:text-white">
                                <option value="num_ticket">Trier par Numéro de Commande</option>
                                <option value="montant_ttc">Trier par Montant</option>
                                <option value="status">Trier par Statut</option>
                            </select>
                        </div>
                    </div>

                    <div className="mt-6 flow-root sm:mt-2">
                        <div className="divide-y divide-gray-200 dark:divide-gray-700">
                            {currentTickets.length === 0 ? (
                                <p className="text-center text-gray-500 dark:text-gray-400">Aucune commande récente.</p>
                            ) : (
                                currentTickets.map((ticket, index) => (
                                    <div key={index} className="flex flex-wrap items-center gap-y-4 py-6">
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                Numéro de commande :
                                            </dt>
                                            <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                <a href="#" className="hover:underline">#{ticket.num_ticket}</a>
                                            </dd>
                                        </dl>
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                Utilisateur :
                                            </dt>
                                            <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                {`${ticket.user?.nom || 'Utilisateur anonyme'} ${ticket.user?.prenom || ''}`}
                                            </dd>
                                        </dl>
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                Montant :
                                            </dt>
                                            <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                {new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF' }).format(ticket.montant_ttc || 0)}
                                            </dd>
                                        </dl>
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">Statut :</dt>
                                            <dd className={`me-2 mt-1.5 inline-flex items-center rounded px-2.5 py-0.5 text-xs font-medium ${ticket.status === "Confirmeé" ? 'bg-green-200 text-green-800' : ticket.status === "En attente" ? 'bg-yellow-500 text-yellow-800' : ticket.status === "Scanné" ? 'bg-blue-200 text-blue-800' : 'bg-red-500 text-white'} dark:bg-${ticket.status === "Confirmeé" ? 'green-900' : ticket.status === "En attente" ? 'yellow-900' : ticket.status === "Scanné" ? 'blue-900' : 'red-900'} dark:text-white`}>
                                                <svg className="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18 17.94 6M18 18 6.06 6" />
                                                </svg>
                                                {ticket.status}
                                            </dd>
                                        </dl>
                                    </div>
                                ))
                            )}
                        </div>
                    </div>

                    {/* Contrôles de pagination */}
                    <div className="mt-4 flex justify-between">
                        <button onClick={handlePrevPage} disabled={currentPage === 1} className="px-4 py-2 text-sm font-medium text-white bg-red-200 rounded-md hover:bg-red-300 disabled:bg-red-500 dark:bg-red-700 dark:text-white  dark:hover:bg-red-600">
                            Précédent
                        </button>
                        <button onClick={handleNextPage} disabled={currentPage === totalPages} className="px-4 py-2 text-sm font-medium text-gray-500 bg-red-200 rounded-md hover:bg-green-300 disabled:bg-green-300 dark:bg-green-700 dark:text-black dark:hover:bg-green-600">
                            Suivant
                        </button>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default RecentTickets;
