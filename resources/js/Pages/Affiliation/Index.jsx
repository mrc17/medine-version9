import ModalCreateAffiliation from '@/Components/ModalCreatePateModal';
import ModalEditAffiliation from '@/Components/ModalEditAffiliation';
import TitrePage from '@/Components/TitrePage';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';

export default function Index({ auth, affiliations, users, compagnies }) {
    const [openDropdown, setOpenDropdown] = useState({});
    const [searchTerm, setSearchTerm] = useState('');
    const [currentPage, setCurrentPage] = useState(1);
    const itemsPerPage = 8;

    const [isOpenModal, setIsOpenModal] = useState(false);
    const [selectedAffiliation, setSelectedAffiliation] = useState(null);
    const [isOpenModalModifier, setIsOpenModalModifier] = useState(false);

    // Filter affiliations based on search term
    const filteredAffiliations = affiliations.filter((affiliation) =>
        affiliation.user.nom.toLowerCase().includes(searchTerm.toLowerCase()) ||
        affiliation.user.prenom.toLowerCase().includes(searchTerm.toLowerCase())
    );

    // Pagination Logic
    const totalPages = Math.ceil(filteredAffiliations.length / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const currentAffiliations = filteredAffiliations.slice(startIndex, startIndex + itemsPerPage);

    const toggleDropdown = (id) => {
        setOpenDropdown((prev) => ({
            ...Object.keys(prev).reduce((acc, key) => {
                if (key !== id.toString()) {
                    acc[key] = false; // Close the dropdown
                }
                return acc;
            }, {}),
            [id]: !prev[id], // Toggle the clicked dropdown
        }));
    };

    const handleKeyDown = (e, id) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault(); // Prevent page scroll
            toggleDropdown(id);
        }
    };

    const handlePageChange = (page) => {
        setCurrentPage(page);
    };

    const getStatusClass = (affiliation) => {
        if (affiliation.user.attempt_logins < 4 && affiliation.user.etat === 1) {
            return 'bg-green-500';
        } else if (affiliation.user.attempt_logins < 4 && affiliation.user.etat === 0) {
            return 'bg-red-500';
        } else {
            return 'bg-yellow-500';
        }
    };

    // Dropdown Menu Component
    const DropdownMenu = ({ affiliation }) => (
        <div className="absolute z-50 w-44 -bottom-15 right-4 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
            <ul className="py-1 text-sm text-gray-700 dark:text-gray-200">
                <li>
                    <Link href={route('affiliation.show', { affiliation })} className="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Afficher</Link>
                </li>
                <li>
                    <button
                        onClick={() => {
                            if (affiliation) {
                                setSelectedAffiliation(affiliation);
                                setIsOpenModalModifier(true);
                            }
                        }}

                        href="#" className="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Modifier</button>
                </li>
                {affiliation.user.etat === 0 && (
                    <li>
                        <Link href="#" className="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Débloquer</Link>
                    </li>
                )}
                <li>
                    <Link href="#" className="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Réinitialiser</Link>
                </li>
            </ul>
            <div className="py-1">
                <Link href="#" className="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Supprimer</Link>
            </div>
        </div>
    );

    const onClose = () => {
        setIsOpenModal(false);
    };

    const onCloseEditModal = () => {
        setIsOpenModalModifier(false);
        setSelectedAffiliation(null); // Reset the selected affiliation
    };


    return (
        <AuthenticatedLayout
            user={auth}
            header={<TitrePage page="Les partenaires" />}
        >
            <Head title="Dashboard" />

            <ModalCreateAffiliation show={isOpenModal} onClose={onClose} users={users} compagnies={compagnies} />
            <ModalEditAffiliation show={isOpenModalModifier} onClose={onCloseEditModal} affiliation={selectedAffiliation} />

            <div className="py-5">
                <div className="w-full mx-auto sm:px-6 lg:px-8">
                    <section className="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
                        <div className="mx-auto max-w-screen-xl px-4 lg:px-12">
                            <div className="bg-white dark:bg-gray-800  relative shadow-md sm:rounded-lg overflow-hidden">
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
                                        <button onClick={() => { setIsOpenModal(true) }} type="button" className="flex items-center bg-green-700 justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                            <svg className="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path clipRule="evenodd" fillRule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                            </svg>
                                            Ajouter un Partenaire
                                        </button>
                                    </div>
                                </div>
                                <div className="overflow-x-auto pb-24">
                                    <table className="w-full  text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" className="px-4 py-3">Nom</th>
                                                <th scope="col" className="px-4 py-3">Prénom</th>
                                                <th scope="col" className="px-4 py-3">Téléphone</th>
                                                <th scope="col" className="px-4 py-3">Rôle</th>
                                                <th scope="col" className="px-4 py-3">Nom de la Compagnie</th>
                                                <th scope="col" className="px-4 py-3">Status du compte</th>
                                                <th scope="col" className="px-4 py-3">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {currentAffiliations.map((affiliation) => (
                                                <tr key={affiliation.id} className="border-b dark:border-gray-700">
                                                    <th scope="row" className="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        {affiliation.user.nom}
                                                    </th>
                                                    <td className="px-4 py-3">{affiliation.user.prenom}</td>
                                                    <td className="px-4 py-3">{affiliation.user.telephone}</td>
                                                    <td className="px-4 py-3">{affiliation.user.role.nom}</td>
                                                    <td className="px-4 py-3">{affiliation.compagnie.nom}</td>
                                                    <td className="px-4 py-3">
                                                        <span className={`inline-flex px-2 text-xs font-medium leading-5 rounded-full ${getStatusClass(affiliation)}`}>
                                                            {affiliation.user.etat === 1 ? 'Actif' : 'Inactif'}
                                                        </span>
                                                    </td>
                                                    <td className="px-4 py-3">
                                                        <button
                                                            id={`dropdownButton-${affiliation.id}`}
                                                            className="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-50 dark:text-white dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                                                            type="button"
                                                            onClick={() => toggleDropdown(affiliation.id)}
                                                            onKeyDown={(e) => handleKeyDown(e, affiliation.id)}
                                                        >
                                                            <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M5.293 9.707a1 1 0 011.414 0L10 13.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                                            </svg>
                                                        </button>
                                                        {openDropdown[affiliation.id] && <DropdownMenu affiliation={affiliation} />}
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                                {/* Pagination */}
                                <nav className="flex justify-between items-center p-4" aria-label="Table navigation">
                                    <span className="text-sm font-normal text-gray-500 dark:text-gray-400">Affichage <span className="font-semibold text-gray-900 dark:text-white">{startIndex + 1}-{startIndex + currentAffiliations.length}</span> sur <span className="font-semibold text-gray-900 dark:text-white">{filteredAffiliations.length}</span></span>
                                    <ul className="inline-flex items-center -space-x-px">
                                        {Array.from({ length: totalPages }).map((_, index) => (
                                            <li key={index}>
                                                <button
                                                    onClick={() => handlePageChange(index + 1)}
                                                    className={`px-3 py-2 leading-tight ${currentPage === index + 1
                                                        ? 'text-blue-600 bg-blue-50 border border-blue-300'
                                                        : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700'} dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white`}
                                                >
                                                    {index + 1}
                                                </button>
                                            </li>
                                        ))}
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
