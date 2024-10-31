<?php

/**
 * Class Copy | src/views/copy.php
 * 
 * @author Innocow
 * @copyright 2020 Innocow
 */

namespace Innocow\Security_Audit\Views;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * There is a limit (4096) to gettext() so for translating text copy in the plugin
 * this custom class has to exist.
 */
class Copy {

    /**
     * The locale of the installation.
     * 
     * @var string
     */
    protected $locale = null;

    /**
     * Constructor. Will attempt to find out the locale of the installation.
     */
    public function __construct() {

        global $locale;

        if ( isset( $locale ) ) {

            $this->locale = $locale;

        }

    }

    /**
     * Return a block of text depending on the given $tag.
     * 
     * @param string $tag A block of texts' specific tag.
     * 
     * @return string A block of text.
     */
    public function get( string $tag ) {

        $copy = null;

        if ( $tag === "about_preamble" ) {

            switch( $this->locale ) {

                default:
                    $copy = "<p>This plugin consists of a series of tests and audits for common Wordpress security issues.</p>";
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":
                    $copy ="<p>Ce plugin se compose d'une série de tests et d'audits pour les problèmes de sécurité Wordpress courants.</p>";
                    break;

            }

        } // -about_preamble

        if ( $tag === "about_advice" ) {

            switch( $this->locale ) {

                default:
                    $copy = <<< HTML
<p>While this plugin tries to be as thorough with its tests as possible, there are some things that can't be tested for. Therefore, the following is a reminder of best practices to have to keep your site and installation secure.</p>
<ol>
    <li>Ensure that your installation, plugins and themes are updated.</li>
    <li>Have an offsite backup and test once a month.</li>
    <li>Use a plugin to limit and lock out failed login attempts.</li>
    <li>Use a plugin to add both two-factor authentication and/or a login captcha.</li>
    <li>Use a plugin to change your default login URL.</li>
    <li>Use a plugin to force strong passwords for accounts.</li>

</ol>
HTML;
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":
                    $copy = <<< HTML
<p>Bien que ce plugin essaie d'être aussi approfondi que possible avec ses tests, il y a certaines choses qui ne peuvent pas être testées. Par conséquent, ce qui suit est un rappel des meilleures pratiques pour conserver votre site et votre installation en toute sécurité.</p>
<ol>
    <li>Assurez-vous que votre installation, vos plugins et vos thèmes sont mis à jour.</li>
    <li>Effectuez une sauvegarde et un test hors site une fois par mois.</li>
    <li>Utilisez un plugin pour limiter et verrouiller les tentatives de connexion qui ont échoué. </li> 
    <li>Utilisez un plugin pour ajouter à la fois une authentification à deux facteurs et / ou un captcha de connexion.</li>
    <li> Utilisez un plugin pour forcer des mots de passe forts pour les comptes.</li>
</ol>
HTML;
                    break;

            }

        } // -about_advice

        if ( $tag === "about_furtherhelp" ) {

            switch( $this->locale ) {

                default:
                    $copy = "<p>If you have any questions or comments about this plugin, reach us at <a target='_blank' href='https://innocow.com/contact'>our contact form</a>!</p>";
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":
                    $copy = "<p>Si vous avez des questions, n'hésitez pas à nous contacter <a target='_blank' href='https://innocow.com/contact'>ici</a>!</p>";
                    break;

            }

        } // -about_furtherhelp


        if ( $tag === "ffpermissions_preamble" ) {

            switch ( $this->locale ) {

                default:
                    $copy = "<p>This page will audit for files and folders that do not match the given permissions criteria.</p>";
                    $copy .= "<p>The settings that you will want will vary depending on your hosting situation. However, generally you'll want to limit read and write access to the 'user' and 'group' classes and restrict the 'other' class to only read access.</p>";
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":
                    $copy = "<p>Cette page vérifie les fichiers et dossiers qui ne correspondent pas aux critères d'autorisations donnés.</p>";
                    $copy .= "<p>Les paramètres que vous souhaitez varieront en fonction de votre situation d'hébergement. Cependant, en règle générale, vous souhaiterez limiter l'accès en lecture et en écriture aux classes «propriétaire» et «groupe» et restreindre la classe «les autres» à un accès en lecture uniquement.</p>";
                    break;

            }

        } // -ffpermissions_preamble

        if ( $tag === "ffownership_preamble" ) {

            switch ( $this->locale ) {

                default:
                    $copy = "<p>This page will audit for files and folders that do not match the given ownership criteria.</p>";
                    $copy .= "<p>The user and group ownership will vary depending on your hosting situation. However, generally the installation files and folders should be owned by the same user that is running the web process. The plugin will attempt to find out the user and group the web process is running as and prefill the fields for convenience.</p>";
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":                                
                    $copy = "<p>Cette page vérifiera les fichiers et dossiers qui ne correspondent pas aux critères de propriété donnés.</p>";
                    $copy .= "<p>La propriété de l'utilisateur et du groupe variera en fonction de votre situation d'hébergement. Toutefois, les fichiers et dossiers d'installation doivent généralement appartenir au même utilisateur qui exécute le processus Web. Le plugin tentera de trouver l'utilisateur et le groupe sous lequel le processus Web s'exécute et de préremplir les champs pour plus de commodité.</p>";
                    break;

            }

        } // -ffownership_preamble

        if ( $tag === "ffownership_id_discovery" ) {

            switch ( $this->locale ) {

                default:
                    $copy = "This plugin was able to discover the uid (<span id='text-uid'>-</span>) and gid (<span id='text-gid'>-</span>) of your web server process.";
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":   
                    $copy = "Ce plugin a pu découvrir l'uid (<span id='text-uid'>-</span>) et le gid (<span id='text-gid'>-</span>) de votre processus de serveur Web. Ils ont été automatiquement saisis dans les champs ci-dessous.";
                    break;                
            }

        } // -ffownership_id_discovery

        if ( $tag === "wpconfiguration_preamble" ) {

            switch ( $this->locale ) {

                default:
                    $copy = "<p>This section will run a series of tests for common Wordpress configuration settings that may be security hazards.</p>";
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":                                
                    $copy = "<p>Cette section exécutera une série de tests pour les paramètres de configuration Wordpress courants qui peuvent constituer des risques de sécurité.</p>";
                    break;

            }

        } // -wpconfiguration_preamble

        if ( $tag === "sysconfiguration_preamble" ) {

            switch ( $this->locale ) {

                default:
                    $copy = "<p>This section will run a series of tests for common hosting configuration settings that may be security hazards.</p>";
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":                                
                    $copy = "<p>Cette section exécutera une série de tests pour les paramètres de configuration d'hébergement courants qui peuvent présenter des risques pour la sécurité.</p>";
                    break;

            }

        } // -sysconfiguration_preamble        

        if ( $tag === "restendp_warning_preamble" ) {

            switch ( $this->locale ) {

                default:
                    $copy = "<p>The audit found certain REST routes that are potentially hazardous.</p>";
                    $copy .= "<p> If these routes are registered and publicly available, they can divulge sensitive site data. The most notable issue is the listing of all user accounts including administrative accounts. This listing makes it easier to brute-force attack administrative accounts by confirming their usernames. Consider disabling these routes.</p>";
                    $copy .= "<p>Note that the (?P[\d+) part of a route is a regular expression. You need to substitute a number in its place. For example: <i>/wp/v2/users/(?P[\d]+)</i> must be used as <i>/wp/v2/users/1</i>.";
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":   
                    $copy = "<p>L'audit a révélé que certaines routes REST sont potentiellement dangereuses</p>";
                    $copy .= "<p>Si ces itinéraires sont enregistrés et accessibles au public, ils peuvent divulguer des données de site sensibles. Le problème le plus notable est la liste de tous les comptes d'utilisateurs, y compris les comptes administratifs. Cette liste facilite l'attaque par force brute des comptes administratifs en confirmant leurs noms d'utilisateur. Pensez à désactiver ces itinéraires.</p>";
                    $copy .= "<p>Notez que la partie (?P[\d+) d'une route est une expression régulière. Vous devez remplacer un numéro à sa place. Par exemple: <i>/wp/v2/users/(?P[\d]+)</i> doit être utilisé comme <i>/wp/v2/users/1</i>.</p>";
                    break;
            }

        } // -restendp_warning_preamble

        if ( $tag === "admusers_warning_preamble" ) {

            switch ( $this->locale ) {

                default:
                    $copy = "<p>The audit found administrator level accounts with commonly used names.</p>";
                    $copy .= "<p>If your site has commonly recognised administrative usernames, it is easier to perform a brute-force attack. Consider renaming these accounts.</p>";
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":
                    $copy = "<p>L'audit a trouvé des comptes de niveau administrateur avec des noms couramment utilisés.</p>";
                    $copy .= "<p>Si votre site a des noms d'utilisateur administratifs généralement reconnus, il est plus facile d'effectuer une attaque par force brute. Pensez à renommer ces comptes.</p>";
                    break;
            }

        } // -admusers_warning_preamble

        if ( $tag === "tablepfx_warning_preable" ) {

            switch ( $this->locale ) {

                default:
                    $copy = "<p>The audit found that your installation is using the default table prefixes (wp_).</p>";
                    $copy .= "<p>If your site uses the default prefixes for the database tables, it makes it easier for SQL injection attacks as table names can be predicted. Consider renaming your table prefixes.</p>";
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":
                    $copy = "<p>L'audit a révélé que votre installation utilise les préfixes de table par défaut (wp_).</p>";
                    $copy .= "<p>Si votre site utilise les préfixes par défaut pour les tables de base de données, cela facilite les attaques par injection SQL car les noms de table peuvent être prédits. Pensez à renommer vos préfixes de table.</p>";
                    break;

            }

        } // -tablepfx_warning_preable

        if ( $tag === "fileedit_warning_preamble" ) {

            switch ( $this->locale ) {

                default:
                    $copy = "<p>The audit found that the online file editor is enabled.</p>";
                    $copy .= "<p>If your site keeps the file editor enabled, it becomes easy to  insert malicious code should your administration panel become compromised. Consider disabling this feature if its not necessary.</p>";
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":   
                    $copy = "<p>L'audit a révélé que l'éditeur de fichiers en ligne est activé.</p>";
                    $copy .= "<p>Si votre site maintient l'éditeur de fichiers activé, il devient facile d'insérer du code malveillant si votre panneau d'administration est compromis. Pensez à désactiver cette fonction si ce n'est pas nécessaire.</p>";
                    break;

            }

        } // -fileedit_warning_preamble

        if ( $tag === "author1_warning_preamble" ) {

            switch ( $this->locale ) {

                default:
                    $copy = "<p>The audit found that author lookup by ID is enabled.</p>";
                    $copy .= "<p>If your site allows the author lookup by id (<a href='/?author=1' target='_blank'>?author=1</a>), brute-force attacks become easier. The attacker only needs to try the password rather than both the username and password. Consider disabling this page search or removing administrative access and renaming the account.</p>";
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":   
                    $copy = "<p>L'audit a révélé que la recherche d'auteur par ID est activée.</p>";
                    $copy .= "<p>Si votre site autorise la recherche de l'auteur par identifiant (<a href='/?author=1' target='_blank'>?author=1</a>), les attaques par force brute deviennent plus faciles. L'attaquant n'a qu'à essayer le mot de passe plutôt que le nom d'utilisateur et le mot de passe. Pensez à désactiver cette recherche de page ou à supprimer l'accès administrateur et à renommer le compte.</p>";
                    break;

            }

        } // -author1_warning_preamble

        if ( $tag === "wplogin_warning_preamble" ) {

            switch ( $this->locale ) {

                default:
                    $copy .= "<p>The audit found that the default login page is available. (<a href='/wp-login.php' target='_blank'>/wp-login.php</a>)</p>";
                    $copy .= "<p>While the risk is minimal, keeping the default login URL makes it easier for attackers to brute-force attack your administration area. Scripts and bots are used to scan websites and try multiple combinations of usernames and passwords against the default login path. Consider renaming this URL.</p>";
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":   
                    $copy .= "<p>L'audit a révélé que la page de connexion par défaut est disponible. (<a href='/wp-login.php' target='_blank'>/wp-login.php</a>)</p>";
                    $copy .= "<p>Bien que le risque soit minime, conserver l'URL de connexion par défaut permet aux attaquants d'attaquer par force brute votre zone d'administration. Les scripts et les robots sont utilisés pour analyser les sites Web et essayer plusieurs combinaisons de noms d'utilisateur et de mots de passe par rapport au chemin de connexion par défaut. Pensez à renommer cette URL.</p>";
                    break;

            }

        } // -wplogin_warning_preamble

        if ( $tag === "autoupd_warning_preamble" ) {

            switch ( $this->locale ) {

                default:
                    $copy .= "<p>The audit found that the Wordpress auto-update feature is disabled.</p>";
                    $copy .= "<p>One of the best ways to keep your site secure is to keep the installation's core updated as well as all plugins that are in use. If your site isn't constantly being logged into by an administrator, it may be a good idea to enable this so the installation is always up-to-date. It's also a good idea to make sure your backups are in order in case an update goes awry. Consider enabling this feature.</p>";
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":   
                    $copy .= "<p>L'audit a révélé que la fonction de mise à jour automatique de Wordpress est désactivée.</p>";
                    $copy .= "<p>L'une des meilleures façons de sécuriser votre site est de maintenir à jour le cœur de l'installation ainsi que tous les plugins utilisés. Si votre site n'est pas connecté en permanence par un administrateur, il peut être judicieux de l'activer afin que l'installation soit toujours à jour. C'est aussi une bonne idée de vous assurer que vos sauvegardes sont en ordre au cas où une mise à jour irait de travers. Pensez à activer cette fonctionnalité.</p>";
                    break;

            }

        } // -autoupd_warning_preamble

        if ( $tag === "sensfx_warning_preamble" ) {

            switch ( $this->locale ) {

                default:
                    $copy .= "<p>The audit found that the following functions are enabled in PHP. These functions are hardly used in the Wordpress ecosystem as they can very easily be exploited to run shell commands on the system itself. Consider disabling these in PHP's configuration.</p>";
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":   
                    $copy .= "<p>L'audit a révélé que les fonctions suivantes sont activées en PHP. Ces fonctions sont rarement utilisées dans l'écosystème Wordpress car elles peuvent très facilement être exploitées pour exécuter des commandes shell sur le système lui-même. Pensez à les désactiver dans la configuration de PHP.</p>";
                    break;

            }

        } // -sensfx_warning_preamble

        if ( $tag === "derror_warning_preamble" ) {

            switch ( $this->locale ) {

                default:
                    $copy .= "<p>The audit found that PHP has error display enabled. This should never be enabled on production or live websites as error messages can contain sensitive data. Consider disabling this.</p>";
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":   
                    $copy .= "<p>L'audit a révélé que l'affichage d'erreur sur PHP était activé. Cela ne devrait jamais être activé sur les sites de production ou en direct car les messages d'erreur peuvent contenir des données sensibles. Pensez à le désactiver.</p>";
                    break;

            }

        } // -derror_warning_preamble


        if ( $tag === "httpctx_warning_preamble" ) {

            switch ( $this->locale ) {

                default:
                    $copy .= "<p>The audit found that this page was not served as a secure page. Consider enabling HTTPS on your hosting provider and installation.</p>";
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":   
                    $copy = "<p>L'audit a révélé que cette page n'était pas servie comme page sécurisée. Pensez à activer HTTPS sur votre fournisseur d'hébergement et votre installation.</p>";
                    break;

            }

        } // -httpctx_warning_preamble

        if ( $tag === "indexf_warning_preamble" ) {

            switch ( $this->locale ) {

                default:
                    $copy .= "<p>The audit found some folders that are openly indexable (directory contents can be viewed.) While this isn't a serious security risk, your site might inadvertenly be revealing sensitive data. Consider disabling indexing as it's an easy fix.</p>";
                    break;

                case "fr_FR":
                case "fr_CA":
                case "fr_BE":   
                    $copy .= "<p>L'audit a révélé que certains dossiers sont ouvertement indexables (le contenu des répertoires peut être affiché.) Bien que cela ne constitue pas un risque de sécurité grave, votre site peut révéler par inadvertance des données sensibles. Pensez à désactiver l'indexation car c'est une solution simple.</p>";
                    break;

            }

        } // -indexf_warning_preamble

        return $copy;

    }

}
