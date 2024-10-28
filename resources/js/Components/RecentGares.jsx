import { Link } from '@inertiajs/react';
import React, { useState } from 'react';
import ModalUpdateGare from './ModalUpdateGare';
import ModalDeleteGare from './ModalDeleteGare';

const RecentGares = ({ gares }) => {
    const [currentPage, setCurrentPage] = useState(1);
    const [searchQuery, setSearchQuery] = useState('');
    const [sortCriteria, setSortCriteria] = useState('id'); // Default sort by gare ID
    const garesPerPage = 20;
    const [dropdownIndex, setDropdownIndex] = useState(null); // State to manage dropdown visibility

    // Filter the stations based on the name search
    const filteredGares = gares.filter(gare => {
        const nom = gare.nom.toLowerCase();
        return nom.includes(searchQuery.toLowerCase());
    });

    // Sort the stations based on the selected criterion (ID or name)
    const sortedGares = [...filteredGares].sort((a, b) => {
        if (sortCriteria === 'id') {
            return a.id - b.id; // Sort by ID
        } else if (sortCriteria === 'nom') {
            return a.nom.localeCompare(b.nom); // Alphabetical sort by name
        } else {
            return 0; // Default: no sorting
        }
    });

    // Calculate the total number of pages
    const totalPages = Math.ceil(sortedGares.length / garesPerPage);

    // Get the stations to display for the current page
    const indexOfLastGare = currentPage * garesPerPage;
    const indexOfFirstGare = indexOfLastGare - garesPerPage;
    const currentGares = sortedGares.slice(indexOfFirstGare, indexOfLastGare);

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
        setDropdownIndex(dropdownIndex === index ? null : index); // Toggle dropdown visibility
    };

    const [selecteGare, setSelecteGare] = useState(null);
    const [modalDeleteGare, setModalDeleteGare] = useState(false);
    const [modalUpdateGare, setModalUpdateGare] = useState(false);

    const onClose = () => {
        setSelecteGare(null)
        setModalUpdateGare(false)
        setModalDeleteGare(false)
    }

    return (
        <section className="bg-white py-8 antialiased rounded-xl dark:bg-gray-900 md:py-10">
            <ModalDeleteGare show={modalDeleteGare} onClose={onClose} gare={selecteGare} />
            <ModalUpdateGare show={modalUpdateGare} onClose={onClose} gare={selecteGare} />
            <div className="mx-auto max-w-screen-xl px-4 2xl:px-0">
                <div className="mx-auto max-w-5xl">
                    <div className="gap-4 sm:flex sm:items-center sm:justify-between">
                        <h2 className="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">
                            Dernières Gares
                        </h2>
                        <div className="mt-4 sm:mt-0">
                            <input
                                type="text"
                                placeholder="Recherche par Nom de la Gare..."
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                className="px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                            />
                        </div>
                        <div className="mt-4 sm:mt-0 sm:ml-4">
                            <select onChange={handleSortChange} className="px-4 py-2 border rounded-md dark:bg-gray-800 dark:text-white">
                                <option value="id">Trier par ID</option>
                                <option value="nom">Trier par Nom</option>
                            </select>
                        </div>
                    </div>

                    <div className="mt-6 flow-root sm:mt-2">
                        <div className="divide-y divide-gray-200 dark:divide-gray-700">
                            {currentGares.length === 0 ? (
                                <p className="text-center text-gray-500 dark:text-gray-400">Aucune gare récente.</p>
                            ) : (
                                currentGares.map((gare, index) => (
                                    <div key={index} className="flex flex-wrap items-center gap-y-4 py-6">
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                ID de la Gare :
                                            </dt>
                                            <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                #{gare.id}
                                            </dd>
                                        </dl>
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                Nom :
                                            </dt>
                                            <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                {gare.nom}
                                            </dd>
                                        </dl>
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                Ville :
                                            </dt>
                                            <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                {gare.ville}
                                            </dd>
                                        </dl>
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">Valide :</dt>
                                            <dd className={`mt-1.5 inline-flex items-center rounded px-2.5 py-0.5 text-xs font-medium ${gare.valide ? 'bg-green-500 text-green-800' : 'bg-red-500 text-red-800'} dark:bg-${gare.valide ? 'green-900' : 'red-900'} dark:text-white`}>
                                                {gare.valide ? 'Valide' : 'Non Valide'}
                                            </dd>
                                        </dl>
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400"></dt>
                                            <dd className="px-4 py-3 flex items-center justify-end">
                                                <button
                                                    onClick={() => toggleDropdown(index)}
                                                    className="inline-flex items-end p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-500"
                                                    type="button"
                                                >
                                                    <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 500-4 2 2 0 000 4z" />
                                                    </svg>
                                                </button>
                                                {/* Dropdown menu */}
                                                {dropdownIndex === index && (
                                                    <div className="absolute z-10 w-44 -bottom-15 right-4 bg-white rounded divide-y divide-gray-500 shadow dark:bg-gray-700 dark:divide-gray-600">
                                                        <ul className="py-1 text-sm text-gray-700 dark:text-gray-200 w-full" aria-labelledby="apple-ipad-air-dropdown-button">
                                                            <li>
                                                                <Link href={route('gare.show', gare)} className="block py-2 px-4 hover:bg-gray-500 dark:hover:bg-gray-600 dark:hover:text-white">Afficher</Link>
                                                            </li>
                                                            <li>
                                                                <button onClick={() => {
                                                                    setSelecteGare(gare)
                                                                    setModalUpdateGare(true)
                                                                     }} className="block w-full text-left py-2 px-4 hover:bg-gray-500 dark:hover:bg-gray-600 dark:hover:text-white">Modifier</button>
                                                            </li>
                                                        </ul>
                                                        <div className="py-1">
                                                            <button onClick={() => {
                                                                 setSelecteGare(gare)
                                                                 setModalDeleteGare(true)
                                                                 }} className="block w-full text-left py-2 px-4 text-sm text-gray-700 hover:bg-gray-500 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Supprimer</button>
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

                    {/* Pagination controls */}
                    <div className="mt-4 flex justify-between">
                        <button onClick={handlePrevPage} disabled={currentPage === 1} className="px-4 py-2 text-sm font-medium text-gray-500 bg-gray-200 rounded-md hover:bg-gray-500 disabled:bg-gray-500 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600">
                            Précédent
                        </button>
                        <button onClick={handleNextPage} disabled={currentPage === totalPages} className="px-4 py-2 text-sm font-medium text-gray-500 bg-gray-200 rounded-md hover:bg-gray-500 disabled:bg-gray-500 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600">
                            Suivant
                        </button>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default RecentGares;
