import React from 'react';
import { Head } from '@inertiajs/react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faUserShield, faLock, faFileAlt, faUsers, faExchangeAlt, faGlobe, faEnvelope } from '@fortawesome/free-solid-svg-icons';
import '../../css/style.css';

function Politics() {
  return (
    <div className="w-full mx-auto bg-gray-500 bg-[url('/images/asset/bg.png')] bg-cover bg-no-repeat">
      <div className='bg-slate-900/80 py-4'>
        <div className="p-8 py-5 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 max-w-3xl mx-auto shadow-lg rounded-lg">
          <h1 className="text-3xl font-extrabold mb-6 text-center text-blue-600">Politique de Confidentialité et Protection des Données Personnelles</h1>
          <Head title='Politique & Confidentialité' />
          
          <section className="mb-8">
            <FontAwesomeIcon icon={faUserShield} className="text-blue-600 w-10 h-10 mb-3" />
            <h2 className="text-2xl font-semibold">1. Collecte et Types de Données</h2>
            <p className="text-gray-700 dark:text-gray-300">
              Medine prend au sérieux la protection de votre vie privée et de vos données personnelles. Nous avons conçu cette Politique de Confidentialité pour vous informer sur les types de données que nous collectons, les raisons de cette collecte, la façon dont elles sont traitées et sécurisées, ainsi que sur vos droits concernant vos informations personnelles.
            </p>
            <ul className="list-disc pl-6 mt-2 text-gray-700 dark:text-gray-300">
              <li>Données d'identification personnelle : Nom, email, numéro de téléphone, etc.</li>
              <li>Données de paiement et transactionnelles : Numéro de carte, historique des achats.</li>
              <li>Données de géolocalisation : Si activées pour l’optimisation des services.</li>
            </ul>
          </section>

          <section className="mb-8">
            <FontAwesomeIcon icon={faLock} className="text-blue-600 w-10 h-10 mb-3" />
            <h2 className="text-2xl font-semibold">2. Sécurité et Protection des Données</h2>
            <p className="text-gray-700 dark:text-gray-300">
              Nous mettons en place des mesures de sécurité rigoureuses pour protéger vos informations contre les accès non autorisés, modifications, divulgations ou destructions de vos données personnelles.
            </p>
          </section>

          <section className="mb-8">
            <FontAwesomeIcon icon={faFileAlt} className="text-blue-600 w-10 h-10 mb-3" />
            <h2 className="text-2xl font-semibold">3. Finalités de l’Utilisation des Données</h2>
            <p className="text-gray-700 dark:text-gray-300">
              Les données collectées sur Medine sont utilisées dans le cadre de l’exécution et de l’amélioration de nos services : gestion des réservations, notifications personnalisées, et analyse de performance pour optimiser l’application.
            </p>
          </section>

          <section className="mb-8">
            <FontAwesomeIcon icon={faUsers} className="text-blue-600 w-10 h-10 mb-3" />
            <h2 className="text-2xl font-semibold">4. Partage et Divulgation des Données</h2>
            <p className="text-gray-700 dark:text-gray-300">
              Medine s'engage à ne pas vendre ni louer vos informations personnelles à des tiers. Toutefois, nous pouvons partager vos informations avec des partenaires de confiance pour améliorer notre service.
            </p>
          </section>

          <section className="mb-8">
            <FontAwesomeIcon icon={faExchangeAlt} className="text-blue-600 w-10 h-10 mb-3" />
            <h2 className="text-2xl font-semibold">5. Transfert International des Données</h2>
            <p className="text-gray-700 dark:text-gray-300">
              En fonction de votre localisation, il se peut que vos données soient transférées vers des pays autres que celui où vous résidez. Nous prenons toutes les mesures nécessaires pour assurer une protection adéquate.
            </p>
          </section>

          <section className="mb-8">
            <FontAwesomeIcon icon={faGlobe} className="text-blue-600 w-10 h-10 mb-3" />
            <h2 className="text-2xl font-semibold">6. Droits des Utilisateurs</h2>
            <p className="text-gray-700 dark:text-gray-300">
              Vous disposez de droits spécifiques en matière de protection des données : accès, rectification, suppression, et opposition au traitement de vos données. Ces droits peuvent être exercés à tout moment via notre support client.
            </p>
          </section>

          <section className="mb-8">
            <FontAwesomeIcon icon={faEnvelope} className="text-blue-600 w-10 h-10 mb-3" />
            <h2 className="text-2xl font-semibold">7. Contact et Assistance</h2>
            <p className="text-gray-700 dark:text-gray-300">
              Pour toute question ou demande concernant cette Politique de Confidentialité, contactez-nous via notre adresse email : 
              <a href="mailto:contacts@cybernexservices.com" className="text-blue-600 hover:underline"> contacts@cybernexservices.com</a>.
            </p>
          </section>

          <footer className="text-center mt-10">
            <p className="text-sm text-gray-500 dark:text-gray-400">© {new Date().getFullYear()} Medine. Tous droits réservés.</p>
          </footer>
        </div>
      </div>
    </div>
  );
}

export default Politics;
