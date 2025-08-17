## translations

For translations, use the files in the `resources/lang` directory. Always use the `en` file as the base.
And update the `en` file with the new translations. The translations will be automatically available in Typescript.

In typescript, you can use the `trans` function to get the translation. Use the dot notation to get the translation for a specific key.
For example, if you have a translation key `welcome.message`, you can get the translation in Typescript like this:
```typescript
import { useLingua } from '@cyberwolf.studio/lingua-react';

const { trans } = useLingua();

const text = trans('welcome.message');
```

The translations will be automatically available in Typescript. No need to pass the translations with inertia/php to the frontend.
