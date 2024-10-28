import React from 'react';
import Modal from '@/Components/Modal'; // Ensure you import your Modal component

const ModalVoirTicket = ({ show, onClose, ticket }) => {
    if (!ticket) return null; // Prevent rendering if there's no ticket data

    return (
        <Modal show={show} onClose={onClose}>
            <div className="relative p-4 text-left bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                <h2 className="text-lg font-bold mb-4">Détails du Ticket</h2>
                <div className="mb-4">
                    <p className="text-sm"><strong>Client:</strong> {ticket.user.nom} {ticket.user.prenom}</p>
                    <p className="text-sm"><strong>Téléphone:</strong> {ticket.user.telephone}</p>
                    <p className="text-sm"><strong>Montant TTC:</strong> {ticket.montant_ttc} francs</p>
                    <p className="text-sm"><strong>Date de Réservation:</strong> {new Date(ticket.date_reservation).toLocaleDateString()}</p>
                    <p className="text-sm"><strong>Heure de Départ:</strong> {ticket.heure_depart}</p>
                    <p className="text-sm"><strong>Status:</strong> {ticket.status}</p>
                    <p className="text-sm"><strong>Mode de Paiement:</strong> {ticket.mode_paiement}</p>
                    <p className="text-sm"><strong>Gare de Départ:</strong> {ticket.gare.nom}</p>
                    <p className="text-sm"><strong>Gare Ville:</strong> {ticket.gare.ville}</p>
                    <p className="text-sm"><strong>Gare Commune:</strong> {ticket.gare.commune}</p>
                    <p className="text-sm"><strong>Numéro de Paiement:</strong> {ticket.numero_paiement}</p>
                    <p className="text-sm"><strong>Référence:</strong> {ticket.reference}</p>
                    <p className="text-sm"><strong>Numéro de Ticket:</strong> {ticket.num_ticket}</p>
                    <p className="text-sm"><strong>Code Ticket:</strong> {ticket.codeticket}</p>
                    <p className="text-sm"><strong>Date Création:</strong> {new Date(ticket.created_at).toLocaleDateString()}</p>
                </div>
                <button
                    onClick={onClose}
                    className="mt-4 px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
                    Fermer
                </button>
            </div>
        </Modal>
    );
};

export default ModalVoirTicket;
