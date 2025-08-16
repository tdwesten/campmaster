declare module '@cyberwolf.studio/lingua-react' {
    /**
     * Type for replacements object used in translations
     */
    export type Replacements = Record<string, string | number>;

    /**
     * Type for translation messages
     */
    export type TranslationMessages = Record<string, string>;

    /**
     * Type for PHP translations
     */
    export type PhpTranslations = Record<string, TranslationMessages>;

    /**
     * Type for locale translations
     */
    export interface LocaleTranslations {
        php: PhpTranslations;
        json: unknown[];
    }

    /**
     * Type for all translations
     */
    export interface Translations {
        translations: Record<string, LocaleTranslations>;
    }

    /**
     * Interface for the return value of the useLingua hook
     */
    export interface UseLinguaReturn {
        /**
         * Current locale
         */
        locale: string;

        /**
         * Translate a string
         * @param key - Translation key
         * @param replacements - Object with replacement values
         * @param pluralize - Whether to pluralize the translation
         */
        trans: (key: string, replacements?: Replacements, pluralize?: boolean) => string;

        /**
         * Translate a pluralized string
         * @param key - Translation key
         * @param number - Count for pluralization
         * @param replacements - Object with replacement values
         */
        transChoice: (key: string, number: number, replacements?: Replacements) => string;

        /**
         * Alias for trans function
         * @param key - Translation key
         * @param replacements - Object with replacement values
         * @param pluralize - Whether to pluralize the translation
         */
        __: (key: string, replacements?: Replacements, pluralize?: boolean) => string;
    }

    /**
     * Props for the LinguaProvider component
     */
    export interface LinguaProviderProps {
        locale: string;
        Lingua: Translations;
        children: React.ReactNode;
    }

    /**
     * React Context for Lingua
     */
    export const LinguaContext: React.Context<{
        locale: string;
        Lingua: Translations;
    }>;

    /**
     * Provider component for Lingua
     */
    export const LinguaProvider: React.FC<LinguaProviderProps>;

    /**
     * Hook for using Lingua translations
     * @returns Object with translation functions and locale
     */
    export function useLingua(): UseLinguaReturn;

    export default useLingua;
}
