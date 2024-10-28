import React, { useState } from 'react';

const RecentCars = ({ cars }) => {
    const [currentPage, setCurrentPage] = useState(1);
    const [searchQuery, setSearchQuery] = useState('');
    const [sortCriteria, setSortCriteria] = useState('id'); // Default sort by car ID
    const carsPerPage = 20;

    // Filter cars based on search query (search by car 'imatriculation')
    const filteredCars = cars.filter(car => {
        const imatriculation = car.imatriculation.toLowerCase();
        return imatriculation.includes(searchQuery.toLowerCase());
    });

    // Sort cars based on selected criteria (ID or place)
    const sortedCars = [...filteredCars].sort((a, b) => {
        if (sortCriteria === 'id') {
            return a.id - b.id; // Sorting numerically by car ID
        } else if (sortCriteria === 'place') {
            return a.place - b.place; // Sorting numerically by place
        } else {
            return 0; // Default: no sorting
        }
    });

    // Calculate total pages
    const totalPages = Math.ceil(sortedCars.length / carsPerPage);

    // Get current cars to display
    const indexOfLastCar = currentPage * carsPerPage;
    const indexOfFirstCar = indexOfLastCar - carsPerPage;
    const currentCars = sortedCars.slice(indexOfFirstCar, indexOfLastCar);

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

    return (
        <section className="bg-white py-8 antialiased rounded-xl dark:bg-gray-900 md:py-10">
            <div className="mx-auto max-w-screen-xl px-4 2xl:px-0">
                <div className="mx-auto max-w-5xl">
                    <div className="gap-4 sm:flex sm:items-center sm:justify-between">
                        <h2 className="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">
                            Dernières Voitures
                        </h2>
                        <div className="mt-4 sm:mt-0">
                            <input
                                type="text"
                                placeholder="Recherche par Immatriculation..."
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                className="px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                            />
                        </div>
                        <div className="mt-4 sm:mt-0 sm:ml-4">
                            <select onChange={handleSortChange} className="px-4 py-2 border rounded-md dark:bg-gray-800 dark:text-white">
                                <option value="id">Trier par ID</option>
                                <option value="place">Trier par Place</option>
                            </select>
                        </div>
                    </div>

                    <div className="mt-6 flow-root sm:mt-2">
                        <div className="divide-y divide-gray-200 dark:divide-gray-700">
                            {currentCars.length === 0 ? (
                                <p className="text-center text-gray-500 dark:text-gray-400">Aucune voiture récente.</p>
                            ) : (
                                currentCars.map((car, index) => (
                                    <div key={index} className="flex flex-wrap items-center gap-y-4 py-6">
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                ID de Voiture :
                                            </dt>
                                            <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                #{car.id}
                                            </dd>
                                        </dl>
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                Immatriculation :
                                            </dt>
                                            <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                {car.imatriculation}
                                            </dd>
                                        </dl>
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">
                                                Place :
                                            </dt>
                                            <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                                {car.place}
                                            </dd>
                                        </dl>
                                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">Valide :</dt>
                                            <dd className={`mt-1.5 inline-flex items-center rounded px-2.5 py-0.5 text-xs font-medium ${car.valide ? 'bg-green-500 text-green-800' : 'bg-red-500 text-red-800'} dark:bg-${car.valide ? 'green-900' : 'red-900'} dark:text-white`}>
                                                {car.valide ? 'Valide' : 'Non Valide'}
                                            </dd>
                                        </dl>
                                    </div>
                                ))
                            )}
                        </div>
                    </div>

                    {/* Pagination Controls */}
                    <div className="mt-4 flex justify-between">
                        <button onClick={handlePrevPage} disabled={currentPage === 1} className="px-4 py-2 text-sm font-medium text-gray-500 bg-gray-200 rounded-md hover:bg-gray-300 disabled:bg-gray-300 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600">
                            Précédent
                        </button>
                        <button onClick={handleNextPage} disabled={currentPage === totalPages} className="px-4 py-2 text-sm font-medium text-gray-500 bg-gray-200 rounded-md hover:bg-gray-300 disabled:bg-gray-300 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600">
                            Suivant
                        </button>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default RecentCars;
