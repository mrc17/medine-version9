import { Link } from '@inertiajs/react';
import React, { useState } from 'react';

const Planification = ({ planifications, heures = [] }) => {
    const [currentPage, setCurrentPage] = useState(1);
    const [searchQuery, setSearchQuery] = useState('');
    const [sortCriteria, setSortCriteria] = useState('id'); // Default sort by planification ID
    const [dropdownIndex, setDropdownIndex] = useState(null); // For handling dropdowns
    const planificationsPerPage = 20;

    // Safely map heures to their corresponding planification using the pivot information
    const heuresMap = heures?.reduce((map, heure) => {
        map[heure.pivot.trajet_id] = heure.heure;
        return map;
    }, {}) || {}; // If heures is undefined, default to an empty object

    // Filter planifications based on search query (search by 'depart' or 'arrivee')
    const filteredPlanifications = planifications.filter(planification => {
        const depart = planification.trajet.depart.toLowerCase();
        const arrivee = planification.trajet.arrivee.toLowerCase();
        return (
            depart.includes(searchQuery.toLowerCase()) ||
            arrivee.includes(searchQuery.toLowerCase())
        );
    });

    // Sort planifications based on selected criteria (ID or price)
    const sortedPlanifications = [...filteredPlanifications].sort((a, b) => {
        if (sortCriteria === 'id') {
            return a.id - b.id; // Sorting numerically by planification ID
        } else if (sortCriteria === 'prix') {
            return parseFloat(a.trajet.prix) - parseFloat(b.trajet.prix); // Sorting numerically by price
        } else {
            return 0; // Default: no sorting
        }
    });

    // Calculate total pages
    const totalPages = Math.ceil(sortedPlanifications.length / planificationsPerPage);

    // Get current planifications to display
    const indexOfLastPlanification = currentPage * planificationsPerPage;
    const indexOfFirstPlanification = indexOfLastPlanification - planificationsPerPage;
    const currentPlanifications = sortedPlanifications.slice(indexOfFirstPlanification, indexOfLastPlanification);

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
        setCurrentPage(1); // Reset to the first page when sorting changes
    };

    const toggleDropdown = (index) => {
        setDropdownIndex(dropdownIndex === index ? null : index); // Toggle dropdown for each index
    };

    return (
        <section className="bg-white py-8 antialiased rounded-xl dark:bg-gray-800 md:py-10">
            <div className="mx-auto max-w-screen-xl px-4 2xl:px-0">
                <div className="mx-auto max-w-5xl">
                    <div className="gap-4 sm:flex sm:items-center sm:justify-between">
                        <h2 className="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">
                            Planifications de Trajets
                        </h2>
                        <div className="mt-4 sm:mt-0">
                            <input
                                type="text"
                                placeholder="Recherche par Départ ou Arrivée..."
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                className="px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                            />
                        </div>
                        <div className="mt-4 sm:mt-0 sm:ml-4">
                            <select onChange={handleSortChange} className="px-4 py-2 border rounded-md dark:bg-gray-800 dark:text-white">
                                <option value="id">Trier par ID</option>
                                <option value="prix">Trier par Prix</option>
                            </select>
                        </div>
                    </div>

                    <div className="mt-6 flow-root sm:mt-2">
                        <div className="divide-y divide-gray-200 dark:divide-gray-700">
                            {currentPlanifications.length === 0 ? (
                                <p className="text-center text-gray-500 dark:text-gray-400">Aucune planification trouvée.</p>
                            ) : (
                                currentPlanifications.map((planification, index) => (
                                    <div key={planification.id} className="flex flex-wrap items-center gap-y-4 py-6">
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                Heure de Départ :
                                            </dt>
                                            <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                {heuresMap[planification.trajet.id]
                                                    ? heuresMap[planification.trajet.id]
                                                    : planification.heure}
                                            </dd>
                                        </dl>
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                Départ :
                                            </dt>
                                            <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                {planification.trajet.depart}
                                            </dd>
                                        </dl>
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                Arrivée :
                                            </dt>
                                            <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                {planification.trajet.arrivee}
                                            </dd>
                                        </dl>
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                Prix :
                                            </dt>
                                            <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                {planification.trajet.prix} FCFA
                                            </dd>
                                        </dl>
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                Date :
                                            </dt>
                                            <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                {planification.date}
                                            </dd>
                                        </dl>
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                Véhicule :
                                            </dt>
                                            <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                {planification.car.imatriculation} (Places: {planification.car.place})
                                            </dd>
                                        </dl>
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400"></dt>
                                            <dd className="px-4 py-3 flex items-center justify-end">
                                                <button
                                                    onClick={() => toggleDropdown(index)}
                                                    className="inline-flex items-end p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                                    type="button"
                                                >
                                                    <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    </svg>
                                                </button>
                                                {/* Dropdown menu */}
                                                {dropdownIndex === index && (
                                                    <div className="absolute z-10 w-44 -bottom-15 right-4 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="apple-imac-20-dropdown-button">
                                                            <li>
                                                                <a href="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Modifier</a>
                                                            </li>
                                                        </ul>
                                                        <div class="py-1">
                                                            <a href="#" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Supprimer</a>
                                                        </div>
                                                    </div>
                                                )}
                                            </dd>
                                        </dl>
                                    </div>
                                ))
                            )}
                        </div>
                    </div>

                    {/* Pagination */}
                    {totalPages > 1 && (
                        <div className="flex justify-between mt-4">
                            <button
                                className="px-4 py-2 text-white bg-gray-500 hover:bg-gray-600 rounded-md"
                                disabled={currentPage === 1}
                                onClick={handlePrevPage}
                            >
                                Précédent
                            </button>
                            <p className="text-gray-500 dark:text-gray-400">Page {currentPage} sur {totalPages}</p>
                            <button
                                className="px-4 py-2 text-white bg-gray-500 hover:bg-gray-600 rounded-md"
                                disabled={currentPage === totalPages}
                                onClick={handleNextPage}
                            >
                                Suivant
                            </button>
                        </div>
                    )}
                </div>
            </div>
        </section>
    );
};

export default Planification;
