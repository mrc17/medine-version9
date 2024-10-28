import React, { useState } from 'react';

const Employe = ({ employes }) => {
    console.log(employes)
    const [currentPage, setCurrentPage] = useState(1);
    const [searchQuery, setSearchQuery] = useState('');
    const [sortCriteria, setSortCriteria] = useState('id'); // Default sort by ID
    const employesPerPage = 20;

    // Filter employees based on search by name or phone
    const filteredEmployes = employes.filter(employe => {
        const nom = employe.user ? `${employe.user.nom} ${employe.user.prenom}`.toLowerCase() : '';
        const telephone = employe.user ? employe.user.telephone.toLowerCase() : '';
        return nom.includes(searchQuery.toLowerCase()) || telephone.includes(searchQuery.toLowerCase());
    });

    // Sort employees based on selected criteria (ID or name)
    const sortedEmployes = [...filteredEmployes].sort((a, b) => {
        if (sortCriteria === 'id') {
            return a.id - b.id;
        } else if (sortCriteria === 'nom') {
            return `${a.user?.nom} ${a.user?.prenom}`.localeCompare(`${b.user?.nom} ${b.user?.prenom}`);
        }
        return 0;
    });

    // Calculate total number of pages
    const totalPages = Math.ceil(sortedEmployes.length / employesPerPage);

    // Get the employees to display for the current page
    const indexOfLastEmploye = currentPage * employesPerPage;
    const indexOfFirstEmploye = indexOfLastEmploye - employesPerPage;
    const currentEmployes = sortedEmployes.slice(indexOfFirstEmploye, indexOfLastEmploye);

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
        setCurrentPage(1);
    };

    return (
        <section className="bg-white py-8 antialiased rounded-xl dark:bg-gray-800 md:py-10">
            <div className="mx-auto max-w-screen-xl px-4 2xl:px-0">
                <div className="mx-auto max-w-5xl">
                    <div className="gap-4 sm:flex sm:items-center sm:justify-between">
                        <h2 className="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Employés</h2>
                        <div className="mt-4 sm:mt-0">
                            <input
                                type="text"
                                placeholder="Recherche par Nom ou Téléphone..."
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
                        <div className="divide-y divide-gray-500 dark:divide-gray-700">
                            {currentEmployes.length === 0 ? (
                                <p className="text-center text-gray-500 dark:text-gray-400">Aucun employé trouvé.</p>
                            ) : (
                                currentEmployes.map((employe) => {
                                    const { user } = employe;
                                    return (
                                        <div key={employe.id} className="flex flex-wrap items-center gap-y-4 py-6">
                                            <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                                <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                    ID de l'Employé :
                                                </dt>
                                                <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                    #{employe.id}
                                                </dd>
                                            </dl>
                                            <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                                <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                    Nom :
                                                </dt>
                                                <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                    {user ? `${user.nom} ${user.prenom}` : 'Utilisateur non trouvé'}
                                                </dd>
                                            </dl>
                                            <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                                <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                    Téléphone :
                                                </dt>
                                                <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                    {user ? user.telephone : 'Utilisateur non trouvé'}
                                                </dd>
                                            </dl>
                                            <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                                <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                    Statut :
                                                </dt>
                                                <dd className={`mt-1.5 inline-flex items-center rounded px-2.5 py-0.5 text-xs font-medium ${employe.valide ? 'bg-green-500 text-green-800' : 'bg-red-500 text-red-800'} dark:bg-${employe.valide ? 'green-900' : 'red-900'} dark:text-white`}>
                                                    {employe.valide ? 'Active' : 'Inactive'}
                                                </dd>
                                            </dl>
                                            <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                                <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                    Rôle :
                                                </dt>
                                                <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                    {user?.role?.nom ? user.role.nom : 'Rôle non trouvé'}
                                                </dd>
                                            </dl>
                                            <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                                <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                    Gare :
                                                </dt>
                                                <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                    {user?.gare_responsable?.nom || user?.gares_caisse?.gare?.nom || user?.gare_comptable?.nom || 'Pas assigné à une gare'}
                                                </dd>
                                            </dl>
                                        </div>
                                    );
                                })
                            )}
                        </div>
                    </div>

                    <div className="mt-4 flex justify-between">
                        <span className="text-sm text-gray-500 dark:text-gray-400">
                            Page {currentPage} sur {totalPages}
                        </span>
                        <div className="flex space-x-2">
                            <button
                                onClick={handlePrevPage}
                                disabled={currentPage === 1}
                                aria-label="Page précédente"
                                className="px-4 py-2 text-sm font-medium text-gray-500 bg-gray-500 rounded-md hover:bg-gray-300 disabled:bg-gray-300 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600"
                            >
                                Précédent
                            </button>
                            <button
                                onClick={handleNextPage}
                                disabled={currentPage === totalPages}
                                aria-label="Page suivante"
                                className="px-4 py-2 text-sm font-medium text-gray-500 bg-gray-500 rounded-md hover:bg-gray-300 disabled:bg-gray-300 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600"
                            >
                                Suivant
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default Employe;
