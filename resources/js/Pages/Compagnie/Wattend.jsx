import { useState } from 'react';
import { Head } from '@inertiajs/react';
import TitrePage from '@/Components/TitrePage';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import ModalRejetCompagnie from '@/Components/ModalRejetCompagnie';
import ModalValideCompagnie from '@/Components/ModalValideCompagnie';

export default function Wattend({ auth, compagnies }) {
    const [searchQuery, setSearchQuery] = useState('');
    const [currentPage, setCurrentPage] = useState(1);
    const [selectedDemande, setSelectedDemande] = useState(null);
    const itemsPerPage = 10;

    const [openModalRejete, setOpenModalRejet] = useState(null);
    const [openModalValide, setOpenModalValide] = useState(null);
    const [selecteCompagnie, setSelecteCompagnie] = useState(null);

    const closeModal = () => {
        setOpenModalRejet(false);
        setOpenModalValide(false);
    }

    const filteredCompagnies = compagnies.filter((demande) => {
        const nomcomplet = `${demande.responsable.nom} ${demande.responsable.prenom}`;

        return (
            demande.sig.includes(searchQuery) ||
            demande.responsable.telephone.includes(searchQuery) ||
            demande.nom.toLowerCase().includes(searchQuery.toLowerCase()) ||
            demande.responsable.nom.toLowerCase().includes(searchQuery.toLowerCase()) ||
            nomcomplet.toLowerCase().includes(searchQuery.toLowerCase()) ||
            demande.responsable.prenom.toLowerCase().includes(searchQuery.toLowerCase())
        );
    });

    const totalItems = filteredCompagnies.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const currentItems = filteredCompagnies.slice(startIndex, startIndex + itemsPerPage);

    const handleDemandeClick = (demande) => {
        setSelectedDemande(demande);
    };

    return (
        <AuthenticatedLayout
            user={auth}
            header={<TitrePage page="Demande de création de compte compagnie" />}
        >
            <Head title="Dashboard" />
            <ModalRejetCompagnie show={openModalRejete} selecteCompagnie={selecteCompagnie} onClose={closeModal} />
            <ModalValideCompagnie show={openModalValide} selecteCompagnie={selecteCompagnie} onClose={closeModal} />
            <div className="py-5 px-4 dark:bg-gray-900">
                <div className="w-full h-full flex flex-col md:flex-row space-x-0 md:space-x-4 mx-auto sm:px-6 lg:px-8">
                    {/* First Section */}
                    <section className="py-8 antialiased w-full md:w-1/2 overflow-auto">
                        <div className="mx-auto max-w-5xl">
                            {/* Search Input */}
                            <div className="mb-4">
                                <input
                                    type="text"
                                    placeholder="Rechercher par nom, contact ou SIRET"
                                    value={searchQuery}
                                    onChange={(e) => setSearchQuery(e.target.value)}
                                    className="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                />
                            </div>

                            {/* Mapping over filtered compagnies */}
                            {currentItems.map((demande, index) => (
                                <div className="mt-6 flow-root sm:mt-8" key={index}>
                                    <div onClick={() => handleDemandeClick(demande)} className="divide-y divide-gray-200 bg-slate-700  p-2 rounded-lg dark:divide-gray-500">
                                        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div className="col-span-3">
                                                <button
                                                    onClick={() => handleDemandeClick(demande)}
                                                    className="content-center font-semibold text-gray-900 hover:underline dark:text-white sm:col-span-10 lg:col-span-3 text-left"
                                                >
                                                    {demande.nom}
                                                </button>
                                                <dl className="items-center">
                                                    <dd className="text-gray-500 dark:text-gray-400">{demande.responsable.nom} {demande.responsable.prenom}</dd>
                                                </dl>
                                            </div>
                                            <div className="col-start-4">
                                                <dd className="text-gray-500 dark:text-gray-400">{demande.responsable.telephone}</dd>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))}

                            {/* No results found message */}
                            {totalItems === 0 && <p className="mt-4 text-gray-500 dark:text-gray-400">Aucun résultat trouvé.</p>}

                            {/* Pagination */}
                            <nav className="mt-6 flex items-center justify-center sm:mt-8" aria-label="Page navigation example">
                                <ul className="flex h-8 items-center -space-x-px text-sm">
                                    <li>
                                        <button
                                            onClick={() => setCurrentPage((prev) => Math.max(prev - 1, 1))}
                                            className={`flex h-8 items-center justify-center rounded-s-lg border border-e-0 border-gray-300 bg-white px-3 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white ${currentPage === 1 ? 'cursor-not-allowed opacity-50' : ''}`}
                                            disabled={currentPage === 1}
                                            aria-label="Previous"
                                        >
                                            <span className="sr-only">Previous</span>
                                            <svg className="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                <path fillRule="evenodd" d="M13.35 16.85a.5.5 0 0 0 .1-.7l-4.2-4.2 4.2-4.2a.5.5 0 0 0-.7-.7l-4.5 4.5a1 1 0 0 0 0 1.4l4.5 4.5a.5.5 0 0 0 .7 0z" clipRule="evenodd" />
                                            </svg>
                                        </button>
                                    </li>
                                    {Array.from({ length: totalPages }, (_, i) => (
                                        <li key={i}>
                                            <button
                                                onClick={() => setCurrentPage(i + 1)}
                                                className={`flex h-8 items-center justify-center border border-gray-300 bg-gray-100 px-4 text-gray-700 hover:bg-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600 ${currentPage === i + 1 ? 'bg-gray-300' : ''}`}
                                                aria-current={currentPage === i + 1 ? "page" : undefined}
                                            >
                                                {i + 1}
                                            </button>
                                        </li>
                                    ))}
                                    <li>
                                        <button
                                            onClick={() => setCurrentPage((prev) => Math.min(prev + 1, totalPages))}
                                            className={`flex h-8 items-center justify-center rounded-e-lg border border-gray-300 bg-white px-3 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white ${currentPage === totalPages ? 'cursor-not-allowed opacity-50' : ''}`}
                                            disabled={currentPage === totalPages}
                                            aria-label="Next"
                                        >
                                            <span className="sr-only">Next</span>
                                            <svg className="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                <path fillRule="evenodd" d="M6.65 3.15a.5.5 0 0 1-.1.7l4.2 4.2-4.2 4.2a.5.5 0 0 1 .7.7l4.5-4.5a1 1 0 0 1 0-1.4l-4.5-4.5a.5.5 0 0 1-.7 0z" clipRule="evenodd" />
                                            </svg>
                                        </button>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </section>

                    {/* Second Section */}
                    <section className="py-8 antialiased w-full md:w-2/3 bg-slate-700 rounded-xl shadow-lg p-2">
                        <h2 className="text-xl font-semibold text-gray-900 dark:text-white">Détails</h2>
                        {selectedDemande ? (
                            <div className="w-full">

                                <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div className="col-span-3 mt-4">
                                        <h3 className="text-lg dark:text-gray-300 font-semibold">{selectedDemande.nom}</h3>
                                        <p className="mt-2 text-gray-700 dark:text-gray-300">Nom de la compagnie: {selectedDemande.nom}</p>
                                        <p className="mt-2 text-gray-700 dark:text-gray-300">Sig de la compagnie: {selectedDemande.sig}</p>
                                        <p className="mt-2 text-gray-700 dark:text-gray-300">Contact de la compagnie: {selectedDemande.contact}</p>
                                        <p className="mt-1 text-gray-700 dark:text-gray-300">Siège: {selectedDemande.localite}</p>
                                        <p className="mt-2 text-gray-700 dark:text-gray-300">Responsable: {selectedDemande.responsable.nom} {selectedDemande.responsable.prenom}</p>
                                        <p className="mt-1 text-gray-700 dark:text-gray-300">Téléphone du responsable: {selectedDemande.responsable.telephone}</p>
                                    </div>
                                    <div className="col-start-4">
                                        {selectedDemande.image ? (
                                            <img src={`storage/${selectedDemande.image}`} alt={`${selectedDemande.nom} logo`} loading="lazy" />
                                        ) : (
                                            <p className="text-gray-500 dark:text-gray-400">Image non disponible.</p>
                                        )}
                                    </div>
                                </div>
                                <div class="flex mt-5 justify-center items-center space-x-4 w-full">
                                    <button
                                        onClick={() => {
                                            setOpenModalRejet(true);
                                            setSelecteCompagnie(selectedDemande);
                                            // Set a specific action for rejection
                                        }}
                                        className='w-1/2 text-white font-bold bg-red-500 p-2 rounded-lg text-center'>
                                        Rejeter
                                    </button>

                                    <button
                                        onClick={() => {
                                            // You can handle confirmation differently here
                                            setOpenModalValide(true);
                                            setSelecteCompagnie(selectedDemande);
                                        }}
                                        className='w-1/2 text-white font-bold p-2 rounded-lg text-center bg-green-500'>
                                        Confirmer
                                    </button>

                                </div>
                            </div>
                        ) : (
                            <p className="mt-4 text-gray-500 dark:text-gray-400">Sélectionnez une compagnie pour voir les détails.</p>
                        )}
                    </section>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}
