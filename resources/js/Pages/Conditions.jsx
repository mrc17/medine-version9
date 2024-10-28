import React from 'react';
import { FaCheckCircle } from 'react-icons/fa';
import { Head } from '@inertiajs/react';

function Conditions() {
  return (
    <div className="w-full mx-auto bg-gray-500 bg-[url('/images/asset/bg.png')] bg-cover bg-no-repeat">
      <div className='bg-slate-900/80 py-4'>
      <Head title='Conditions Générales' />
        <div className="p-8 py-5 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 max-w-3xl mx-auto shadow-lg rounded-lg">
          <h1 className="text-3xl md:text-4xl font-bold text-center mb-6 text-blue-600">Conditions Générales d'Utilisation</h1>
          <section className="mb-6">
            <h2 className="text-xl font-semibold">1. Introduction</h2>
            <p className="mb-4">
              Bienvenue sur Medine, une application dédiée à la gestion et à la réservation de services de transport. Les présentes Conditions Générales d'Utilisation (CGU) régissent votre accès et votre utilisation de notre application. En accédant à notre service, vous acceptez ces conditions dans leur intégralité. Si vous n'acceptez pas ces conditions, veuillez ne pas utiliser notre service. Medine se réserve le droit de modifier ces conditions à tout moment. Les modifications seront publiées sur cette page, et il est de votre responsabilité de consulter régulièrement ces CGU.
            </p>
          </section>

          <section className="mb-6">
            <h2 className="text-xl font-semibold">2. Définitions</h2>
            <ul className="list-disc list-inside mb-4">
              <li><FaCheckCircle className="inline text-blue-600 mr-2" /> <strong>Service</strong> : désigne l'application Medine, y compris toutes les fonctionnalités et services associés, ainsi que tout contenu proposé via l'application.</li>
              <li><FaCheckCircle className="inline text-blue-600 mr-2" /> <strong>Utilisateur</strong> : désigne toute personne utilisant l'application, que ce soit en tant qu'invité ou en tant que membre inscrit.</li>
              <li><FaCheckCircle className="inline text-blue-600 mr-2" /> <strong>Données Personnelles</strong> : désigne toutes les informations permettant d'identifier une personne, telles que le nom, l'adresse e-mail, le numéro de téléphone, etc.</li>
              <li><FaCheckCircle className="inline text-blue-600 mr-2" /> <strong>Conditions Générales</strong> : désigne les présentes conditions générales d'utilisation qui régissent l'utilisation de l'application.</li>
            </ul>
          </section>

          <section className="mb-6">
            <h2 className="text-xl font-semibold">3. Acceptation des Conditions</h2>
            <p className="mb-4">
              En créant un compte et en utilisant notre Service, vous reconnaissez avoir lu, compris et accepté ces Conditions Générales. Si vous êtes un mineur, vous devez obtenir le consentement d'un parent ou d'un tuteur avant d'utiliser notre Service. Medine se réserve le droit de modifier ces Conditions à tout moment, et il est de votre responsabilité de rester informé des mises à jour. L'utilisation continue du Service après publication des modifications constitue votre acceptation de ces modifications.
            </p>
          </section>

          <section className="mb-6">
            <h2 className="text-xl font-semibold">4. Inscription et Compte Utilisateur</h2>
            <p className="mb-4">
              Pour utiliser certaines fonctionnalités de notre Service, vous devez créer un compte en fournissant des informations exactes, complètes et à jour. Vous êtes responsable de la sécurité de votre mot de passe et de toutes les activités qui se produisent sous votre compte. Vous vous engagez à notifier immédiatement Medine de toute utilisation non autorisée de votre compte ou de toute autre violation de sécurité. Medine ne sera pas responsable des pertes ou des dommages résultant de votre incapacité à protéger votre compte ou vos informations d'identification.
            </p>
          </section>

          <section className="mb-6">
            <h2 className="text-xl font-semibold">5. Utilisation du Service</h2>
            <p className="mb-4">
              Vous vous engagez à utiliser notre Service uniquement à des fins légales et conformément aux lois en vigueur. Vous ne devez pas :
            </p>
            <ul className="list-disc list-inside mb-4">
              <li><FaCheckCircle className="inline text-blue-600 mr-2" /> Utiliser le Service d'une manière qui pourrait nuire, désactiver, surcharger ou altérer le Service ou perturber l'utilisation d'autres utilisateurs.</li>
              <li><FaCheckCircle className="inline text-blue-600 mr-2" /> Tenter d'accéder à des systèmes ou réseaux non autorisés, en violant des mesures de sécurité ou d'authentification.</li>
              <li><FaCheckCircle className="inline text-blue-600 mr-2" /> Publier, transmettre ou diffuser du contenu illégal, nuisible, menaçant, abusif, harcelant, diffamatoire ou autrement inapproprié.</li>
              <li><FaCheckCircle className="inline text-blue-600 mr-2" /> Utiliser le Service pour transmettre des publicités non sollicitées, du spam, ou toute autre forme de sollicitation non autorisée.</li>
              <li><FaCheckCircle className="inline text-blue-600 mr-2" /> Utiliser des robots, des araignées, des scrapeurs ou d'autres moyens automatisés pour accéder au Service sans autorisation préalable.</li>
            </ul>
          </section>

          <section className="mb-6">
            <h2 className="text-xl font-semibold">6. Propriété Intellectuelle</h2>
            <p className="mb-4">
              Tous les contenus, marques, logos, et autres éléments de propriété intellectuelle présents dans l'application sont la propriété exclusive de Medine ou de ses partenaires. Vous vous engagez à ne pas reproduire, distribuer, afficher ou créer des œuvres dérivées basées sur le Service ou son contenu sans notre autorisation écrite préalable. Toute utilisation non autorisée du Service ou de son contenu peut entraîner des poursuites judiciaires.
            </p>
          </section>

          <section className="mb-6">
            <h2 className="text-xl font-semibold">7. Protection des Données Personnelles</h2>
            <p className="mb-4">
              Nous nous engageons à protéger la confidentialité de vos Données Personnelles. Les informations que vous fournissez lors de votre utilisation de notre Service seront traitées conformément à notre Politique de Confidentialité. Nous collectons, utilisons et partageons vos Données Personnelles uniquement pour les finalités énoncées dans notre Politique de Confidentialité, et vous avez le droit de demander l'accès à vos données, leur rectification ou leur suppression.
            </p>
          </section>

          <section className="mb-6">
            <h2 className="text-xl font-semibold">8. Limitation de Responsabilité</h2>
            <p className="mb-4">
              Dans toute la mesure permise par la loi, Medine ne sera pas responsable des pertes indirectes, dommages, pertes de profits, ou toute autre perte résultant de l'utilisation ou de l'incapacité à utiliser le Service. Medine ne garantit pas que le Service sera exempt d'erreurs, de virus ou d'autres éléments nuisibles. Vous êtes seul responsable de votre utilisation du Service et de toute conséquence qui en découle.
            </p>
          </section>

          <section className="mb-6">
            <h2 className="text-xl font-semibold">9. Modifications du Service</h2>
            <p className="mb-4">
              Medine se réserve le droit de modifier, de suspendre ou d'interrompre, temporairement ou définitivement, le Service, avec ou sans préavis. Vous acceptez que Medine ne sera pas responsable envers vous ou envers un tiers pour toute modification, suspension ou interruption du Service. Nous pouvons également imposer des restrictions sur certaines fonctionnalités ou services ou limiter votre accès à certaines parties du Service, sans responsabilité envers vous.
            </p>
          </section>

          <section className="mb-6">
            <h2 className="text-xl font-semibold">10. Résiliation</h2>
            <p className="mb-4">
              Medine se réserve le droit de suspendre ou de résilier votre compte et votre accès au Service, à sa seule discrétion, si nous estimons que vous avez violé ces CGU ou que votre utilisation du Service nuit à d'autres utilisateurs. En cas de résiliation, vous devez cesser toute utilisation du Service et nous informer de toute utilisation ultérieure.
            </p>
          </section>

          <section className="mb-6">
            <h2 className="text-xl font-semibold">11. Droit Applicable</h2>
            <p className="mb-4">
              Ces Conditions Générales sont régies par les lois en vigueur dans le pays où Medine est enregistrée. Tout litige découlant de l'utilisation de notre Service sera soumis à la juridiction exclusive des tribunaux compétents de cette juridiction.
            </p>
          </section>

          <section className="mb-6">
            <h2 className="text-xl font-semibold">12. Contact</h2>
            <p className="mb-4">
              Pour toute question concernant ces Conditions Générales, veuillez nous contacter à l'adresse suivante : <strong>contact@medine.com</strong>.
            </p>
          </section>

          <footer className="mt-8 text-center">
            <p className="text-sm">© {new Date().getFullYear()} Medine. Tous droits réservés.</p>
          </footer>
        </div>
      </div>
    </div>
  );
}

export default Conditions;
