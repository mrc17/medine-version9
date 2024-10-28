import ModalDeleteGare from '@/Components/ModalDeleteGare';
import ModalUpdateGare from '@/Components/ModalUpdateGare';
import TitrePage from '@/Components/TitrePage';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';

export default function Index({ auth, gares }) {
    console.log(gares);
    const [openDropdown, setOpenDropdown] = useState({});
    const [searchTerm, setSearchTerm] = useState('');
    const [currentPage, setCurrentPage] = useState(1);
    const itemsPerPage = 5;

    const [selecteGare, setSelecteGare] = useState(null);
    const [modalUpdateGare, setModalUpdateGare] = useState(false);
    const [modalDeleteGare, setModalDeleteGare] = useState(false);

    // Filter Gares based on search term
    const filteredGares = gares.filter(gare =>
        gare.nom.toLowerCase().includes(searchTerm.toLowerCase())
    );

    // Pagination Logic
    const totalPages = Math.ceil(filteredGares.length / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const currentGares = filteredGares.slice(startIndex, startIndex + itemsPerPage);

    const toggleDropdown = (id) => {
        setOpenDropdown((prev) => ({
            ...prev,
            [id]: !prev[id],
        }));
    };

    const handlePageChange = (page) => {
        setCurrentPage(page);
    };

    const onClose = () => {
        setSelecteGare(null)
        setModalUpdateGare(false)
        setModalDeleteGare(false)
    }

    return (
        <AuthenticatedLayout
            user={auth}
            header={<TitrePage page="Gares" />}
        >
            <Head title="Dashboard" />
            <ModalDeleteGare show={modalDeleteGare} onClose={onClose} gare={selecteGare} />
            <ModalUpdateGare show={modalUpdateGare} onClose={onClose} gare={selecteGare} />
            <div className="py-5">
                <div className="w-full mx-auto sm:px-6 lg:px-8">
                    <section className="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
                        <div className="mx-auto max-w-screen-xl px-4 lg:px-12">
                            <div className="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                                <div className="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                                    <div className="w-full md:w-1/2">
                                        <form className="flex items-center">
                                            <label htmlFor="simple-search" className="sr-only">Search</label>
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
                                                    placeholder="Search"
                                                    value={searchTerm}
                                                    onChange={(e) => setSearchTerm(e.target.value)}
                                                    required
                                                />
                                            </div>
                                        </form>
                                    </div>
                                    <div className="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                        <button type="button" className="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                            <svg className="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path clipRule="evenodd" fillRule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                            </svg>
                                            Add product
                                        </button>
                                    </div>
                                </div>
                                <div className="overflow-x-auto">
                                    <table className="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" className="px-4 py-3">Nom</th>
                                                <th scope="col" className="px-4 py-3">sig</th>
                                                <th scope="col" className="px-4 py-3">contact</th>
                                                <th scope="col" className="px-4 py-3">localite</th>
                                                <th scope="col" className="px-4 py-3">Status</th>
                                                <th scope="col" className="px-4 py-3">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {currentGares.map((gare) => (
                                                <tr key={gare.id} className="border-b dark:border-gray-700">
                                                    <th scope="row" className="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        {gare.nom}
                                                    </th>
                                                    <td className="px-4 py-3">{gare.compagnie.sig}</td>
                                                    <td className="px-4 py-3">{gare.compagnie.contact}</td>
                                                    <td className="px-4 py-3">{gare.compagnie.localite}</td>
                                                    <td className="px-4 py-4">
                                                        {gare.valide ? (
                                                            <div className="w-5 h-5 rounded-full bg-green-500"></div>
                                                        ) : (
                                                            <div className="w-5 h-5 rounded-full bg-red-500"></div>
                                                        )}
                                                    </td>
                                                    <td className="px-4 py-3 flex items-center justify-center">
                                                        <button onClick={() => toggleDropdown(gare.id)} className="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" type="button">
                                                            <svg className="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                            </svg>
                                                        </button>
                                                        {openDropdown[gare.id] && (
                                                            <div className="absolute z-50 w-44 -bottom-15 right-4 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                                                <ul className="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdown-button">
                                                                    <li>
                                                                        <Link href={route('gare.show', { gare })} className="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Afficher</Link>
                                                                    </li>
                                                                    <li>
                                                                        <button onClick={() => {
                                                                            setSelecteGare(gare)
                                                                            setModalUpdateGare(true)
                                                                        }} href="#" className="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Modifier</button>
                                                                    </li>
                                                                </ul>
                                                                <div className="py-1">
                                                                    <button
                                                                        onClick={() => {
                                                                            setSelecteGare(gare)
                                                                            setModalDeleteGare(true)
                                                                        }}
                                                                        href="#" className="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Supprimer</button>
                                                                </div>
                                                            </div>
                                                        )}
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                                <div className="flex justify-between p-4">
                                    <button
                                        disabled={currentPage === 1}
                                        onClick={() => handlePageChange(currentPage - 1)}
                                        className="bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-l hover:bg-gray-400 disabled:opacity-50"
                                    >
                                        Précédent
                                    </button>
                                    <span>
                                        Page {currentPage} of {totalPages}
                                    </span>
                                    <button
                                        disabled={currentPage === totalPages}
                                        onClick={() => handlePageChange(currentPage + 1)}
                                        className="bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-r hover:bg-gray-400 disabled:opacity-50"
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
