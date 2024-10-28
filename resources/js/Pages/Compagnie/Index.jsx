import ModalDeleteCompagnie from '@/Components/ModalDeleteCompagnie';
import TitrePage from '@/Components/TitrePage';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';

export default function Index({ auth, compagnies }) {
    const [searchTerm, setSearchTerm] = useState('');
    const [currentPage, setCurrentPage] = useState(1);
    const itemsPerPage = 5; // Nombre d'éléments par page
    const [deleteCompagnie, setDeleteCompagnie] = useState(false);
    const [openDropdownIndex, setOpenDropdownIndex] = useState(null); // Suivre quel dropdown est ouvert
    const [selectedCompagnie, setSelectedCompagnie] = useState(null);

    const onClose = () => {
        setDeleteCompagnie(false);
        setSelectedCompagnie(null);
    };

    // Filtrer les compagnies en fonction du terme de recherche
    const filteredCompagnies = compagnies.filter(compagnie =>
        compagnie.nom.toLowerCase().includes(searchTerm.toLowerCase())
    );

    // Logique de pagination
    const totalPages = Math.ceil(filteredCompagnies.length / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const currentCompagnies = filteredCompagnies.slice(startIndex, startIndex + itemsPerPage);

    const handlePageChange = (pageNumber) => {
        if (pageNumber >= 1 && pageNumber <= totalPages) {
            setCurrentPage(pageNumber);
        }
    };

    const toggleDropdown = (index) => {
        setOpenDropdownIndex(openDropdownIndex === index ? null : index); // Basculer le dropdown pour la ligne spécifique
    };

    return (
        <AuthenticatedLayout user={auth} header={<TitrePage page="Liste des compagnies" />}>
            <Head title="Dashboard" />

            <div className="py-5">
                <div className="w-full mx-auto sm:px-6 lg:px-8">
                    <section className="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
                        <ModalDeleteCompagnie show={deleteCompagnie} compagnie={selectedCompagnie} onClose={onClose} />
                        <div className="mx-auto max-w-screen-xl px-4 lg:px-12">
                            <div className="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                                <div className="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                                    <div className="w-full md:w-1/2">
                                        <form className="flex items-center" onSubmit={(e) => e.preventDefault()}>
                                            <label htmlFor="simple-search" className="sr-only">Rechercher</label>
                                            <div className="relative w-full">
                                                <div className="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg aria-hidden="true" className="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fillRule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clipRule="evenodd" />
                                                    </svg>
                                                </div>
                                                <input
                                                    type="text"
                                                    id="simple-search"
                                                    className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                    placeholder="Rechercher"
                                                    required
                                                    value={searchTerm}
                                                    onChange={(e) => setSearchTerm(e.target.value)} // Mettre à jour l'état searchTerm
                                                />
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div className="overflow-x-auto">
                                    <table className="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" className="px-4 py-3">Nom</th>
                                                <th scope="col" className="px-4 py-3">Sig</th>
                                                <th scope="col" className="px-4 py-3">Contact</th>
                                                <th scope="col" className="px-4 py-3">Localité</th>
                                                <th scope="col" className="px-4 py-3">Statut</th>
                                                <th scope="col" className="px-4 py-3">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {currentCompagnies.map((compagnie, index) => (
                                                <tr key={index} className="border-b dark:border-gray-700">
                                                    <th scope="row" className="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        {compagnie.nom}
                                                    </th>
                                                    <td className="px-4 py-3">{compagnie.sig}</td>
                                                    <td className="px-4 py-3">{compagnie.contact}</td>
                                                    <td className="px-4 py-3">{compagnie.localite}</td>
                                                    <td className="px-4 py-4">
                                                        {compagnie.valide ? (
                                                            <div className="w-5 h-5 rounded-full bg-green-500"></div>
                                                        ) : (
                                                            <div className="w-5 h-5 bg-red-500"></div>
                                                        )}
                                                    </td>
                                                    <td className="px-4 py-3 flex items-center justify-center">
                                                        <button
                                                            onClick={() => toggleDropdown(index)}
                                                            className="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                                            type="button"
                                                        >
                                                            <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                            </svg>
                                                        </button>
                                                        {openDropdownIndex === index && (
                                                            <div className="absolute z-50 w-44 right-5 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                                                <ul className=" text-sm text-gray-700 dark:text-gray-200">
                                                                    <li>
                                                                        <Link href={route('compagnies.show', compagnie)} className="block py-3 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Afficher</Link>
                                                                    </li>
                                                                </ul>
                                                                <div className="w-full">
                                                                    <button onClick={() => {
                                                                        setDeleteCompagnie(true);
                                                                        setSelectedCompagnie(compagnie);
                                                                    }} className="block text-left py-3 px-4 w-full text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Supprimer</button>
                                                                </div>
                                                            </div>
                                                        )}
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                                {/* Contrôles de pagination */}
                                <div className="flex justify-between items-center p-4">
                                    <button
                                        onClick={() => handlePageChange(currentPage - 1)}
                                        disabled={currentPage === 1}
                                        className="px-4 py-2 bg-blue-500 text-white rounded disabled:opacity-50"
                                    >
                                        Précédent
                                    </button>
                                    <span>Page {currentPage} de {totalPages}</span>
                                    <button
                                        onClick={() => handlePageChange(currentPage + 1)}
                                        disabled={currentPage === totalPages}
                                        className="px-4 py-2 bg-blue-500 text-white rounded disabled:opacity-50"
                                    >
                                        Suivant
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
