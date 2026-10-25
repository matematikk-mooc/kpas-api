(function () {
  'use strict';

  // --- Complete Hardcoded Translations Dictionary ---
  const TRANSLATIONS = {
    nb: {
      common: {
        site_title: 'Kompetanseportalen',
        brand_sub: 'Utdanningsdirektoratet',
        skip_to_content: 'Hopp til hovedinnhold',
        top_login: 'Logg inn',
        obs_title: 'OBS!',
        obs_text: 'Vennligst benytt "Meld deg på" dersom du skal registrere ny bruker.',
        feide_login: 'Feide',
        canvas_login: 'Canvas',
        breadcrumbs_here: 'Du er her:',
        breadcrumbs_home: 'Forside',
        lang_name: 'Bokmål',
        lang_code: 'NB',
        alt_lang_name: 'Nynorsk',
        alt_lang_code: 'NN',
        lang_switch_label: 'Velg språk',
        lang_not_available: 'Denne siden er ikke tilgjengelig på det valgte språket. Nedenfor finner du originalinnholdet på bokmål.',
        scroll_to_top: 'Til toppen',
        footer_delivered_by: 'Kompetansepakker er levert av Utdanningsdirektoratet',
        footer_link_about: 'Om kompetansepakker',
        footer_link_contact: 'Kontakt',
        footer_link_privacy: 'Personvernerklæring',
        footer_a11y_label: 'Tilgjengelighetserklæring på',
        footer_a11y_nb: 'Bokmål',
        footer_a11y_nn: 'Nynorsk',
        nav_about: 'Om kompetansepakker',
        nav_contact: 'Kontakt',
        nav_privacy: 'Personvern'
      },
      about: {
        page_title: 'Om kompetanseportalen',
        breadcrumb_title: 'Om kompetanseportalen',
        lead: 'Kompetanseportalen er en plattform som tilbyr gratis nettbaserte kompetansepakker.',
        p1: 'Disse pakkene er utviklet for å støtte kompetanseutvikling hos ansatte i barnehage, skole og fagopplæring, både individuelt og kollektivt. Pakkene varierer i lengde og tema, men har til felles at de bidrar til refleksjon og utvikling av praksis.',
        p2: 'Plattformen er utviklet i samarbeid med flere universiteter og høgskoler for å tilby Norges største videreutdanninger for lærere:',
        partners: [
          'MatematikkMOOC - Universitetet i Tromsø (UiT) og Høgskolen i Sør-Trøndelag (HiST)',
          'Matematikk 1-MOOC - Universitetet i Agder (UiA)',
          'PfDK-MOOC - Universitetet i Sørøst-Norge (USN) og Høgskolen på Vestlandet (HVL)'
        ],
        h2_further_edu: 'Åpne videreutdanninger',
        p_further_edu: 'Alle etterutdanningsversjonene av disse videreutdanningene er åpent tilgjengelige i plattformen. Ønsker du å ta videreutdanningene for studiepoeng, kan du finne mer informasjon om opptak og gjennomføring hos de respektive institusjonene som tilbyr kursene.',
        h2_open_source: 'Åpen kildekode',
        p_open_source: 'Både plattformen og designet er utviklet som åpen kildekode og er tilgjengelig for nedlasting. Dette betyr at utviklere kan bidra til forbedring eller gjenbruke koden i andre prosjekter. Du finner kildekoden her: <a href="https://github.com/matematikk-mooc/frontend" target="_blank" rel="noopener noreferrer">GitHub-repositoriet for frontend.</a>',
        h2_fagfornyelsen: 'Fagfornyelsen',
        p_fagfornyelsen: 'I tillegg til videreutdanningene, er det også utviklet en egen kompetansepakke knyttet til fagfornyelsen. Denne pakken er laget i samarbeid med Høgskolen i Innlandet (HiNN). Vi har også hatt god støtte fra Utdanningsetaten i Tromsø kommune i utforskingen av tekniske løsninger som gjør det mulig å tildele ulike roller til skoleledere, lærere og eiere, samt dele dem inn i grupper.',
        h2_icons: 'Ikoner og grafiske elementer',
        p_icons: 'Noen av ikonene som brukes på plattformen er hentet fra Font Awesome, som tilbyr gratis ikoner under åpen lisens.'
      },
      contact: {
        page_title: 'Kontakt',
        breadcrumb_title: 'Kontakt',
        lead: 'Velkommen til Kompetanseportalen - en plattform utviklet av Utdanningsdirektoratet i samarbeid med flere universiteter og høgskoler.',
        p1: 'Vi tilbyr gratis nettbaserte kompetansepakker for ansatte i barnehager, skoler og fagopplæring, med mål om å støtte faglig utvikling og praksisrefleksjon.',
        p2: 'Plattformen vår er designet for å gi brukerne enkel tilgang til videre- og etterutdanning, enten individuelt eller i grupper. Vi streber etter å tilby den beste opplevelsen for brukerne våre og er her for å hjelpe deg.',
        h2_faq: 'Ofte stilte spørsmål (FAQ)',
        faq: [
          {
            q: 'Hvordan registrerer jeg meg på kompetanse.udir.no?',
            a: '<p>Du kan registrere deg på plattformen ved å bruke Feide-pålogging eller ved å registrere deg manuelt med navn og e-postadresse. For manuell registrering, meld deg på en kompetansepakke og følg instruksjonene for å opprette en brukerkonto.</p>'
          },
          {
            q: 'Jeg har registrert meg men får ikke logget inn. Hva gjør jeg?',
            a: '<p>For å logge inn her må du være påmeldt minst en kompetansepakke.</p>'
          },
          {
            q: 'Hvordan kan jeg få tilgang til kompetansepakkene?',
            a: '<p>Når du er registrert og innlogget på plattformen, kan du utforske og melde deg på de ulike kompetansepakkene fra hovedmenyen. Alle pakkene er gratis og tilgjengelige for alle registrerte brukere.</p>'
          },
          {
            q: 'Hvordan kan jeg slette kontoen min?',
            a: '<p>Du kan når som helst slette brukerkontoen din ved å gå inn på innstillinger oppe til høyre og velge undermenyen «Brukersletting». Dette starter en prosess der du må verifisere en kode sendt på e-post og har en angrefrist på en måned før brukerdataen permanent slettes. Merk at dette vil fjerne all tilknyttet informasjon og fremdrift på plattformen.</p><p>Du kan også kontakte vår brukerstøtte på <a href="mailto:kompetansesupport@udir.no">kompetansesupport@udir.no</a> for hjelp til sletting av kontoen din.</p>'
          }
        ],
        h2_contact_info: 'Kontaktinformasjon',
        p_contact_info: 'Hvis du ikke finner svar på spørsmålet ditt i FAQ-seksjonen, er vi her for å hjelpe deg. For spørsmål om plattformen, registrering eller teknisk støtte, vennligst kontakt oss på <a href="mailto:kompetansesupport@udir.no">kompetansesupport@udir.no</a>.'
      },
      privacy: {
        page_title: 'Personvernerklæring',
        breadcrumb_title: 'Personvern',
        lead: 'Her finner du informasjon om hvordan personopplysningene dine behandles når du bruker Kompetanseportalen.',
        cookie_text: 'Kompetanseportalen bruker informasjonskapsler for å forbedre brukeropplevelsen, blant annet ved å huske språket du har valgt. De fleste nettlesere aksepterer automatisk informasjonskapsler, men du kan selv endre innstillingene for å blokkere eller tillate dem.',
        h2_faq: 'Vanlige spørsmål om behandling av personopplysninger',
        faq: [
          {
            q: 'Hvordan registrerer jeg meg, og hvilke opplysninger behandles?',
            a: '<p>Du kan registrere deg ved å bruke Feide-pålogging eller ved å oppgi navn og e-post. Ved bruk av Feide kan følgende personopplysninger bli behandlet:</p><ul><li>Fult navn</li><li>Navn og ID for vertsorganisasjon</li><li>Organisasjonsnummer</li><li>E-postadresse og mobilnummer</li><li>Foretrukket språk</li><li>Sted</li></ul><p>Hvis du registrerer deg med navn og e-post, vil følgende opplysninger bli behandlet:</p><ul><li>Fult navn</li><li>E-postadresse</li><li>Domenenavnet knyttet til e-posten (f.eks. arbeidsgivers domene)</li></ul><p>Dette er nødvendig for å sikre sikker innlogging til plattformen. Les mer: <a href="https://www.feide.no/personvern-og-samtykke" target="_blank" rel="noopener noreferrer">Feides personvernerklæring</a></p>'
          },
          {
            q: 'Hvilke personopplysninger behandles når jeg bruker kompetansepakker?',
            a: '<p>Formålet med å behandle personopplysninger i Kompetanseportalen er å sørge for at du kan gjennomføre pakkene og få den kompetanseheving de gir. Følgende opplysninger kan bli lagret:</p><ul><li>Svar på oppgaver og aktiviteter</li><li>Status for gjennomføring (fullført, delvis fullført eller ikke påbegynt)</li><li>Tidsbruk og innloggingsdetaljer</li></ul><p>Informasjonen er kun tilgjengelig for deg og Utdanningsdirektoratet eller deres leverandører. I enkelte tilfeller kan du dele svarene dine med andre, f.eks. gjennom «hverandrevurdering» eller tilbakemelding fra fagansvarlige.</p><h3>Bruk av Sentry for feil- og ytelsesovervåking</h3><p>Vi bruker Sentry, en feil- og ytelsesovervåkningstjeneste levert av Sentry, Inc., for å identifisere, diagnostisere og rette tekniske feil i våre digitale tjenester. Sentry samler automatisk inn feilmeldinger, tekniske hendelser og ytelsesdata fra våre systemer for å gi innsikt i hva som gikk galt og hvordan vi kan forbedre stabilitet og brukeropplevelse. Data som samles kan inkludere systeminformasjon, feilkoder og sporingsdetaljer, men ikke persondata som navn eller e-post med mindre dette er eksplisitt sendt ved en feilhendelse. Hensikten med behandlingen er å forbedre driftssikkerheten, oppdage problemer tidlig og sikre at tjenestene fungerer som forventet.</p>'
          },
          {
            q: 'Hvordan fungerer diskusjonsforumene på plattformen?',
            a: '<p>Kompetanseportalen inneholder diskusjonsforum hvor brukere kan kommunisere med hverandre. Disse forumene er åpne for alle registrerte brukere.</p>'
          },
          {
            q: 'Hva slags statistikk lages om bruken av kompetansepakkene?',
            a: '<p>Utdanningsdirektoratet samler anonym statistikk om:</p><ul><li>Antall brukere som har besøkt en kompetansepakke</li><li>Hvor mange som har registrert seg i en kompetansepakke, og hvilken rolle de har og hvilken gruppe de er i</li></ul><p>Denne statistikken er offentlig, men er anonymisert for å hindre at enkeltpersoner kan gjenkjennes. Utdanningsdirektoratet kan også bruke e-post for å kontakte registrerte brukere med oppdateringer eller forespørsler om evalueringer av kompetansepakkene.</p>'
          },
          {
            q: 'Hva er det rettslige grunnlaget for behandlingen av personopplysninger?',
            a: '<h3>Registrering og innlogging</h3><p>Behandlingen av personopplysninger i forbindelse med registrering og innlogging er basert på personvernforordningen artikkel 6 nr. 1 bokstav f. Dette gir Utdanningsdirektoratet rett til å behandle opplysninger for å sikre plattformens sikkerhet.</p><h3>Bruk av kompetansepakker</h3><p>Personopplysninger som behandles når du bruker kompetansepakkene, er basert på personvernforordningen artikkel 6 nr. 1 bokstav e, der oppdragsbrev fra Kunnskapsdepartementet fungerer som rettslig grunnlag.</p>'
          },
          {
            q: 'Hvem er behandlingsansvarlig?',
            a: '<p>Utdanningsdirektoratet er behandlingsansvarlig for alle personopplysninger knyttet til Kompetanseportalen, bortsett fra Feide-data, hvor vertsorganisasjonen har ansvaret. Utdanningsdirektoratet har også en databehandleravtale med Sikt, som drifter plattformen teknisk.</p>'
          },
          {
            q: 'Hvor lenge lagres personopplysningene mine?',
            a: '<p>Personopplysningene blir slettet dersom brukeren ikke har logget inn i plattformen på 3 år. Du kan når som helst slette brukerkontoen din og innholdet du har lastet opp ved å gå inn på innstillinger oppe til høyre og velge undermenyen «Brukersletting». Dette starter en prosess der du må verifisere en kode sendt på e-post og har en angrefrist på en måned før brukerdataen permanent slettes fra canvas. Du kan også kontakte oss på <a href="mailto:kompetansesupport@udir.no">kompetansesupport@udir.no</a> for hjelp til sletting av brukerdata.</p>'
          },
          {
            q: 'Hvilke rettigheter har jeg som en bruker?',
            a: '<p>Som bruker har du rett til:</p><ul><li>Innsyn i hvilke opplysninger som er lagret om deg</li><li>Retting av feilaktige opplysninger</li><li>Sletting av personopplysninger, der dette er mulig</li></ul><p>Du kan utøve disse rettighetene ved å kontakte <a href="mailto:kompetansesupport@udir.no">kompetansesupport@udir.no</a>.</p>'
          }
        ],
        h2_complaint: 'Klage',
        p_complaint: 'Hvis du mener at vi behandler personopplysninger i strid med lovgivningen, kan du kontakte vårt personvernombud på <a href="mailto:personvernombud@udir.no">personvernombud@udir.no</a> eller klage til Datatilsynet. Du finner kontaktinformasjon her: <a href="https://www.datatilsynet.no/om-datatilsynet/kontakt-oss/" target="_blank" rel="noopener noreferrer">Datatilsynet</a>.'
      }
    },
    nn: {
      common: {
        site_title: 'Kompetanseportalen',
        brand_sub: 'Utdanningsdirektoratet',
        skip_to_content: 'Gå til hovudinnhaldet',
        top_login: 'Logg inn',
        obs_title: 'OBS!',
        obs_text: 'Ver vennleg og nyt "Meld deg på" dersom du skal registrere ny brukar.',
        feide_login: 'Feide',
        canvas_login: 'Canvas',
        breadcrumbs_here: 'Du er her:',
        breadcrumbs_home: 'Forside',
        lang_name: 'Nynorsk',
        lang_code: 'NN',
        alt_lang_name: 'Bokmål',
        alt_lang_code: 'NB',
        lang_switch_label: 'Vel språk',
        lang_not_available: 'Denne sida er ikkje tilgjengeleg på det valde språket. Nedanfor finn du originalinnhaldet på bokmål.',
        scroll_to_top: 'Til toppen',
        footer_delivered_by: 'Kompetansepakkane er levert av Utdanningsdirektoratet',
        footer_link_about: 'Om kompetansepakkar',
        footer_link_contact: 'Kontakt',
        footer_link_privacy: 'Personvernerklæring',
        footer_a11y_label: 'Tilgjengelegheitserklæring på',
        footer_a11y_nb: 'Bokmål',
        footer_a11y_nn: 'Nynorsk',
        nav_about: 'Om kompetansepakkar',
        nav_contact: 'Kontakt',
        nav_privacy: 'Personvern'
      },
      about: {
        page_title: 'Om kompetanseportalen',
        breadcrumb_title: 'Om kompetanseportalen',
        lead: 'Kompetanseportalen er en plattform som tilbyr gratis nettbaserte kompetansepakker.',
        has_fallback_warning: true,
        p1: 'Disse pakkene er utviklet for å støtte kompetanseutvikling hos ansatte i barnehage, skole og fagopplæring, både individuelt og kollektivt. Pakkene varierer i lengde og tema, men har til felles at de bidrar til refleksjon og utvikling av praksis.',
        p2: 'Plattformen er utviklet i samarbeid med flere universiteter og høgskoler for å tilby Norges største videreutdanninger for lærere:',
        partners: [
          'MatematikkMOOC - Universitetet i Tromsø (UiT) og Høgskolen i Sør-Trøndelag (HiST)',
          'Matematikk 1-MOOC - Universitetet i Agder (UiA)',
          'PfDK-MOOC - Universitetet i Sørøst-Norge (USN) og Høgskolen på Vestlandet (HVL)'
        ],
        h2_further_edu: 'Åpne videreutdanninger',
        p_further_edu: 'Alle etterutdanningsversjonene av disse videreutdanningene er åpent tilgjengelige i plattformen. Ønsker du å ta videreutdanningene for studiepoeng, kan du finne mer informasjon om opptak og gjennomføring hos de respektive institusjonene som tilbyr kursene.',
        h2_open_source: 'Åpen kildekode',
        p_open_source: 'Både plattformen og designet er utviklet som åpen kildekode og er tilgjengelig for nedlasting. Dette betyr at utviklere kan bidra til forbedring eller gjenbruke koden i andre prosjekter. Du finner kildekoden her: <a href="https://github.com/matematikk-mooc/frontend" target="_blank" rel="noopener noreferrer">GitHub-repositoriet for frontend.</a>',
        h2_fagfornyelsen: 'Fagfornyelsen',
        p_fagfornyelsen: 'I tillegg til videreutdanningene, er det også utviklet en egen kompetansepakke knyttet til fagfornyelsen. Denne pakken er laget i samarbeid med Høgskolen i Innlandet (HiNN). Vi har også hatt god støtte fra Utdanningsetaten i Tromsø kommune i utforskingen av tekniske løsninger som gjør det mulig å tildele ulike roller til skoleledere, lærere og eiere, samt dele dem inn i grupper.',
        h2_icons: 'Ikoner og grafiske elementer',
        p_icons: 'Noen av ikonene som brukes på plattformen er hentet fra Font Awesome, som tilbyr gratis ikoner under åpen lisens.'
      },
      contact: {
        page_title: 'Kontakt',
        breadcrumb_title: 'Kontakt',
        lead: 'Velkommen til Kompetanseportalen - en plattform utviklet av Utdanningsdirektoratet i samarbeid med flere universiteter og høgskoler.',
        has_fallback_warning: true,
        p1: 'Vi tilbyr gratis nettbaserte kompetansepakker for ansatte i barnehager, skoler og fagopplæring, med mål om å støtte faglig utvikling og praksisrefleksjon.',
        p2: 'Plattformen vår er designet for å gi brukerne enkel tilgang til videre- og etterutdanning, enten individuelt eller i grupper. Vi streber etter å tilby den beste opplevelsen for brukerne våre og er her for å hjelpe deg.',
        h2_faq: 'Ofte stilte spørsmål (FAQ)',
        faq: [
          {
            q: 'Hvordan registrerer jeg meg på kompetanse.udir.no?',
            a: '<p>Du kan registrere deg på plattformen ved å bruke Feide-pålogging eller ved å registrere deg manuelt med navn og e-postadresse. For manuell registrering, meld deg på en kompetansepakke og følg instruksjonene for å opprette en brukerkonto.</p>'
          },
          {
            q: 'Jeg har registrert meg men får ikke logget inn. Hva gjør jeg?',
            a: '<p>For å logge inn her må du være påmeldt minst en kompetansepakke.</p>'
          },
          {
            q: 'Hvordan kan jeg få tilgang til kompetansepakkene?',
            a: '<p>Når du er registrert og innlogget på plattformen, kan du utforske og melde deg på de ulike kompetansepakkene fra hovedmenyen. Alle pakkene er gratis og tilgjengelige for alle registrerte brukere.</p>'
          },
          {
            q: 'Hvordan kan jeg slette kontoen min?',
            a: '<p>Du kan når som helst slette brukerkontoen din ved å gå inn på innstillinger oppe til høyre og velge undermenyen «Brukersletting». Dette starter en prosess der du må verifisere en kode sendt på e-post og har en angrefrist på en måned før brukerdataen permanent slettes. Merk at dette vil fjerne all tilknyttet informasjon og fremdrift på plattformen.</p><p>Du kan også kontakte vår brukerstøtte på <a href="mailto:kompetansesupport@udir.no">kompetansesupport@udir.no</a> for hjelp til sletting av kontoen din.</p>'
          }
        ],
        h2_contact_info: 'Kontaktinformasjon',
        p_contact_info: 'Hvis du ikke finner svar på spørsmålet ditt i FAQ-seksjonen, er vi her for å hjelpe deg. For spørsmål om plattformen, registrering eller teknisk støtte, vennligst kontakt oss på <a href="mailto:kompetansesupport@udir.no">kompetansesupport@udir.no</a>.'
      },
      privacy: {
        page_title: 'Personvernerklæring',
        breadcrumb_title: 'Personvern',
        lead: 'Her finn du informasjon om korleis personopplysningane dine blir behandla når du bruker Kompetanseportalen.',
        cookie_text: 'Kompetanseportalen bruker informasjonskapslar for å forbetre brukaropplevinga, mellom anna ved å hugse språket du har valt. Dei fleste nettlesarane aksepterer automatisk informasjonskapslar, men du kan sjølv endre instillingane for å blokkere eller tillate dei.',
        h2_faq: 'Vanlege spørsmål om behandling av personopplysningar',
        faq: [
          {
            q: 'Korleis registrerer eg meg, og kva for opplysningar blir behandla?',
            a: '<p>Du kan registrere deg ved å bruke Feide-pålogging eller ved å oppgi namn og e-post. Ved bruk av Feide kan følgande personopplysningar bli behandla:</p><ul><li>Fullt namn</li><li>Namn og ID for vertsorganisasjonen</li><li>Organisasjonsnummer</li><li>E-postadresse og mobilnummer</li><li>Føretrekt språk</li><li>Stad</li></ul><p>Hvis du registrerer deg med namn og epost, vil følgande opplysningar bli behandla:</p><ul><li>Fullt namn</li><li>E-postadresse</li><li>Domenenamnet knytt til e-posten (t.d. arbeidsgivaren sitt domene)</li></ul><p>Dette er nødvendig for å sikre sikker innlogging til plattforma. Les meir: <a href="https://www.feide.no/personvern-og-samtykke" target="_blank" rel="noopener noreferrer">Feide si personvernerklæring</a></p>'
          },
          {
            q: 'Kva for personopplysningar blir behandla når eg bruker kompetansepakkar?',
            a: '<p>Formålet med å behandle personopplysningar i Kompetanseportalen er å sørge for at du kan gjennomføre pakkane og få den kompetansehevinga dei gir. Følgande opplysningar kan bli lagra:</p><ul><li>Svar på oppgåver og aktivitetar</li><li>Status for gjennomføring (fullført, delvis fullført eller ikkje påbyrja)</li><li>Tidsbruk og innloggingsdetaljar</li></ul><p>Informasjonen er kun tilgjengeleg for deg og Utdanningsdirektoratet eller leverandørane deira. I enkelte tilfelle kan du dele svara dine med andre, t.d. gjennom «kvarandrevurdering» eller tilbakemelding frå fagansvarlege.</p><h3>Bruk av Sentry for feil- og ytelsesovervaking</h3><p>Vi bruker Sentry, ei feil- og ytelsesovervakingsteneste levert av Sentry, Inc., for å identifisere, diagnostisere og rette tekniske feil i våre digitale tenester. Sentry samlar automatisk inn feilmeldingar, tekniske hendingar og ytelsesdata frå systema våre for å gi innsikt i kva som gjekk gale og korleis vi kan forbetre stabilitet og brukaropplevinga. Data som blir samla kan inkludere systeminformasjon, feilkodar og sporingsdetaljar, men ikkje persondata som namn eller e-post med mindre dette er eksplisitt sendt ved ei feilhending. Hensikta med behandlinga er å forbetre driftstryggleiken, oppdage problem tidleg og sikre at tenestene fungerer som forventa.</p>'
          },
          {
            q: 'Korleis fungerer diskusjonsforuma på plattforma?',
            a: '<p>Kompetanseportalen inneheld diskusjonsforum der brukarar kan kommunisere med kvarandre. Desse foruma er opne for alle registrerte brukarar.</p>'
          },
          {
            q: 'Kva slags statistikk blir laga om bruken av kompetansepakkane?',
            a: '<p>Utdanningsdirektoratet samlar anonym statistikk om:</p><ul><li>Tala på brukarar som har besøkt ein kompetansepakke</li><li>Kor mange som har registrert seg i ein kompetansepakke, og kva slags rolle dei har og kva gruppe dei er i</li></ul><p>Denne statistikken er offentleg, men den er anonymisert for å hindre at enkeltpersonar kan kjennast igjen. Utdanningsdirektoratet kan også bruke e-post for å kontakte registrerte brukarar med oppdateringar eller førespurnadar om evalueringar av kompetansepakkane.</p>'
          },
          {
            q: 'Kva er det rettslege grunnlaget for behandling av personopplysningar?',
            a: '<h3>Registrering og innlogging</h3><p>Behandling av personopplysningar i samband med registrering og innlogging er basert på personvernforordninga artikkel 6 nr. 1 bokstav f. Dette gir Utdanningsdirektoratet rett til å behandle opplysningar for å sikre tryggleiken til plattforma.</p><h3>Bruk av kompetansepakkar</h3><p>Personopplysningar som blir behandla når du bruker kompetansepakkane, er basert på personvernforordninga artikkel 6 nr. 1 bokstav e, der oppdragsbrev frå Kunnskapsdepartementet fungerer som rettsleg grunnlag.</p>'
          },
          {
            q: 'Kven er behandlingsansvarleg?',
            a: '<p>Utdanningsdirektoratet er behandlingsansvarleg for alle personopplysningar knytt til Kompetanseportalen, bortsett frå Feide-data, der vertsorganisasjonen har ansvaret. Utdanningsdirektoratet har også ein databehandlaravtale med Sikt, som driftar plattforma teknisk.</p>'
          },
          {
            q: 'Kor lenge blir personopplysningane mine lagra?',
            a: '<p>Personopplysningene blir sletta dersom brukaren ikkje har logga inn i plattforma på 3 år. Du kan når som helst slette brukarkontoen din og innhaldet du har lasta opp ved å gå inn på innstillingar oppe til høgre og velje undermenyen «Slett meg». Dette startar ein prosess der du må verifisere ein kode sendt på e-post og har ei angrefrist på ein månad før brukardataen blir permanent sletta frå canvas. Du kan også kontakte oss på <a href="mailto:kompetansesupport@udir.no">kompetansesupport@udir.no</a> for hjelp til sletting av brukardata.</p>'
          },
          {
            q: 'Kva slags rettar har eg som brukar?',
            a: '<p>Som brukar har du rett til:</p><ul><li>Innsyn i kva slags opplysningar som er lagra om deg</li><li>Retting av feilaktige opplysningar</li><li>Sletting av personopplysningar, der dette er mogleg</li></ul><p>Du kan utøve desse rettane ved å kontakte <a href="mailto:kompetansesupport@udir.no">kompetansesupport@udir.no</a>.</p>'
          }
        ],
        h2_complaint: 'Klage',
        p_complaint: 'Viss du meiner at vi behandlar personopplysningar i strid med lovgivinga, kan du kontakte personvernombudet vårt på <a href="mailto:personvernombud@udir.no">personvernombud@udir.no</a> eller klage til Datatilsynet. Du finn kontaktinformasjonen her: <a href="https://www.datatilsynet.no/om-datatilsynet/kontakt-oss/" target="_blank" rel="noopener noreferrer">Datatilsynet</a>.'
      }
    }
  };

  // --- State Management ---
  function getInitialLang() {
    const urlParams = new URLSearchParams(window.location.search);
    const paramLang = urlParams.get('lang');
    if (paramLang && (paramLang === 'nn' || paramLang === 'nb')) {
      return paramLang;
    }
    const stored = localStorage.getItem('kp_info_lang');
    if (stored && (stored === 'nn' || stored === 'nb')) {
      return stored;
    }
    return 'nb';
  }

  function getPageKey() {
    // 1. Explicit data attribute
    const pageAttr = document.body.getAttribute('data-page');
    if (pageAttr && ['about', 'contact', 'privacy'].includes(pageAttr)) {
      return pageAttr;
    }
    // 2. Query parameter (?page=contact)
    const urlParams = new URLSearchParams(window.location.search);
    const pageParam = urlParams.get('page');
    if (pageParam) {
      if (pageParam.includes('kontakt') || pageParam === 'contact') return 'contact';
      if (pageParam.includes('personvern') || pageParam === 'privacy') return 'privacy';
      if (pageParam.includes('om-kompetanseportalen') || pageParam === 'about') return 'about';
    }
    // 3. Pathname
    const path = window.location.pathname.toLowerCase();
    if (path.includes('kontakt')) return 'contact';
    if (path.includes('personvern')) return 'privacy';
    if (path.includes('om-kompetanseportalen') || path.includes('om-kompetansepakker')) return 'about';

    return 'about';
  }

  let currentLang = getInitialLang();
  let currentPage = getPageKey();

  function setLanguage(newLang) {
    if (newLang !== 'nb' && newLang !== 'nn') return;
    currentLang = newLang;
    localStorage.setItem('kp_info_lang', newLang);
    document.documentElement.lang = newLang;

    // Update URL param without refreshing
    const url = new URL(window.location.href);
    url.searchParams.set('lang', newLang);
    window.history.replaceState({}, '', url.toString());

    render();
  }

  function setPage(newPageKey) {
    if (!['about', 'contact', 'privacy'].includes(newPageKey)) return;
    currentPage = newPageKey;
    document.body.setAttribute('data-page', newPageKey);

    const url = new URL(window.location.href);
    url.searchParams.set('page', newPageKey);
    window.history.replaceState({}, '', url.toString());

    render();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  // --- Render Function ---
  function render() {
    const tCommon = TRANSLATIONS[currentLang].common;
    const tPage = TRANSLATIONS[currentLang][currentPage];

    // Document Title
    document.title = `${tPage.page_title} | ${tCommon.site_title} - Udir`;

    // 1. Skip Link
    const skipLink = document.getElementById('skip-link');
    if (skipLink) skipLink.textContent = tCommon.skip_to_content;

    // 2. Header Brand & Badges
    const brandLink = document.getElementById('brand-link');
    if (brandLink) {
      brandLink.setAttribute('aria-label', `${tCommon.site_title} - ${tCommon.breadcrumbs_home}`);
      brandLink.textContent = tCommon.site_title;
    }

    // 3. Language Selector Button & Dropdown
    const langBtnText = document.getElementById('lang-btn-text');
    if (langBtnText) langBtnText.textContent = currentLang.toUpperCase();

    const langToggleBtn = document.getElementById('lang-toggle-btn');
    if (langToggleBtn) langToggleBtn.setAttribute('aria-label', tCommon.lang_switch_label);

    const langBtnNb = document.getElementById('lang-opt-nb');
    const langBtnNn = document.getElementById('lang-opt-nn');
    if (langBtnNb) langBtnNb.classList.toggle('active', currentLang === 'nb');
    if (langBtnNn) langBtnNn.classList.toggle('active', currentLang === 'nn');

    // 4. Login Elements
    const loginBtn = document.getElementById('login-btn');
    if (loginBtn) loginBtn.textContent = tCommon.top_login;

    const loginObsText = document.getElementById('login-obs-text');
    if (loginObsText) {
      loginObsText.innerHTML = `<b>${tCommon.obs_title}</b> ${tCommon.obs_text}`;
    }

    // 6. Breadcrumbs
    const bcHere = document.getElementById('bc-here');
    if (bcHere) bcHere.textContent = tCommon.breadcrumbs_here;

    const bcHome = document.getElementById('bc-home');
    if (bcHome) bcHome.textContent = tCommon.breadcrumbs_home;

    const bcCurrent = document.getElementById('bc-current');
    if (bcCurrent) bcCurrent.textContent = tPage.breadcrumb_title;

    // 7. Page Content (Title, Lead, Alert, Body)
    const pageTitle = document.getElementById('page-title');
    if (pageTitle) pageTitle.textContent = tPage.page_title;

    const pageLead = document.getElementById('page-lead');
    if (pageLead) pageLead.textContent = tPage.lead;

    const alertContainer = document.getElementById('alert-container');
    if (alertContainer) {
      if (tPage.has_fallback_warning) {
        alertContainer.innerHTML = `
          <div class="alert-box" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
              <line x1="12" y1="9" x2="12" y2="13"/>
              <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            <div>${tCommon.lang_not_available}</div>
          </div>
        `;
      } else {
        alertContainer.innerHTML = '';
      }
    }

    renderArticleBody();

    // 8. Footer
    const footerDelivered = document.getElementById('footer-delivered');
    if (footerDelivered) footerDelivered.textContent = tCommon.footer_delivered_by;

    const footerLinkAbout = document.getElementById('footer-link-about');
    if (footerLinkAbout) footerLinkAbout.textContent = tCommon.footer_link_about;

    const footerLinkContact = document.getElementById('footer-link-contact');
    if (footerLinkContact) footerLinkContact.textContent = tCommon.footer_link_contact;

    const footerLinkPrivacy = document.getElementById('footer-link-privacy');
    if (footerLinkPrivacy) footerLinkPrivacy.textContent = tCommon.footer_link_privacy;

    const footerA11yLabel = document.getElementById('footer-a11y-label');
    if (footerA11yLabel) footerA11yLabel.textContent = tCommon.footer_a11y_label;

    const scrollTopBtnText = document.getElementById('scroll-top-btn-text');
    if (scrollTopBtnText) scrollTopBtnText.textContent = tCommon.scroll_to_top;
  }

  function renderArticleBody() {
    const container = document.getElementById('article-dynamic-body');
    if (!container) return;

    const tPage = TRANSLATIONS[currentLang][currentPage];

    if (currentPage === 'about') {
      container.innerHTML = `
        <p>${tPage.p1}</p>
        <p>${tPage.p2}</p>
        <ul>
          ${tPage.partners.map(p => `<li>${p}</li>`).join('')}
        </ul>
        <h2>${tPage.h2_further_edu}</h2>
        <p>${tPage.p_further_edu}</p>
        <h2>${tPage.h2_open_source}</h2>
        <p>${tPage.p_open_source}</p>
        <h2>${tPage.h2_fagfornyelsen}</h2>
        <p>${tPage.p_fagfornyelsen}</p>
        <h2>${tPage.h2_icons}</h2>
        <p>${tPage.p_icons}</p>
      `;
    } else if (currentPage === 'contact') {
      container.innerHTML = `
        <p>${tPage.p1}</p>
        <p>${tPage.p2}</p>
        <h2>${tPage.h2_faq}</h2>
        <div class="accordion" id="contact-faq-accordion" role="region" aria-label="${tPage.h2_faq}">
          ${tPage.faq.map((item, idx) => `
            <div class="accordion-item">
              <h3 class="accordion-header">
                <button type="button" class="accordion-trigger" id="acc-header-${idx}" aria-expanded="false" aria-controls="acc-panel-${idx}">
                  <span class="accordion-icon-wrapper" aria-hidden="true">
                    <svg class="accordion-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                  </span>
                  <span class="accordion-header-title">${item.q}</span>
                </button>
              </h3>
              <div class="accordion-panel" id="acc-panel-${idx}" role="region" aria-labelledby="acc-header-${idx}">
                <div class="accordion-content">${item.a}</div>
              </div>
            </div>
          `).join('')}
        </div>
        <h2>${tPage.h2_contact_info}</h2>
        <p>${tPage.p_contact_info}</p>
      `;
      setupAccordions(container);
    } else if (currentPage === 'privacy') {
      container.innerHTML = `
        <p>${tPage.cookie_text}</p>
        <h2>${tPage.h2_faq}</h2>
        <div class="accordion" id="privacy-faq-accordion" role="region" aria-label="${tPage.h2_faq}">
          ${tPage.faq.map((item, idx) => `
            <div class="accordion-item">
              <h3 class="accordion-header">
                <button type="button" class="accordion-trigger" id="acc-header-p-${idx}" aria-expanded="false" aria-controls="acc-panel-p-${idx}">
                  <span class="accordion-icon-wrapper" aria-hidden="true">
                    <svg class="accordion-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                  </span>
                  <span class="accordion-header-title">${item.q}</span>
                </button>
              </h3>
              <div class="accordion-panel" id="acc-panel-p-${idx}" role="region" aria-labelledby="acc-header-p-${idx}">
                <div class="accordion-content">${item.a}</div>
              </div>
            </div>
          `).join('')}
        </div>
        <h2>${tPage.h2_complaint}</h2>
        <p>${tPage.p_complaint}</p>
      `;
      setupAccordions(container);
    }
  }

  // --- Accessible Accordion Controller ---
  function setupAccordions(scope) {
    const triggers = scope.querySelectorAll('.accordion-trigger');
    triggers.forEach(trigger => {
      trigger.addEventListener('click', () => {
        const isExpanded = trigger.getAttribute('aria-expanded') === 'true';
        const panelId = trigger.getAttribute('aria-controls');
        const panel = document.getElementById(panelId);

        trigger.setAttribute('aria-expanded', String(!isExpanded));
        if (panel) {
          panel.classList.toggle('open', !isExpanded);
        }
      });
    });
  }

  // --- Global Event Listeners ---
  function initEvents() {
    // 0. Skip Link Focus Handling
    const skipLink = document.getElementById('skip-link');
    if (skipLink) {
      skipLink.addEventListener('click', () => {
        const target = document.getElementById('main-content');
        if (target) {
          target.focus();
        }
      });
    }

    // 1. Language Toggle Button
    const langBtn = document.getElementById('lang-toggle-btn');
    const langDropdown = document.getElementById('lang-dropdown');
    if (langBtn && langDropdown) {
      langBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = langDropdown.classList.contains('open');
        langDropdown.classList.toggle('open', !isOpen);
        langBtn.setAttribute('aria-expanded', String(!isOpen));
      });
    }

    const langBtnNb = document.getElementById('lang-opt-nb');
    const langBtnNn = document.getElementById('lang-opt-nn');
    if (langBtnNb) {
      langBtnNb.addEventListener('click', () => {
        setLanguage('nb');
        if (langDropdown) langDropdown.classList.remove('open');
        if (langBtn) {
          langBtn.setAttribute('aria-expanded', 'false');
          langBtn.focus();
        }
      });
    }
    if (langBtnNn) {
      langBtnNn.addEventListener('click', () => {
        setLanguage('nn');
        if (langDropdown) langDropdown.classList.remove('open');
        if (langBtn) {
          langBtn.setAttribute('aria-expanded', 'false');
          langBtn.focus();
        }
      });
    }

    // 2. Login Dropdown
    const loginBtn = document.getElementById('login-btn');
    const loginDropdown = document.getElementById('login-dropdown');
    if (loginBtn && loginDropdown) {
      loginBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = loginDropdown.classList.contains('open');
        loginDropdown.classList.toggle('open', !isOpen);
        loginBtn.setAttribute('aria-expanded', String(!isOpen));
      });
    }

    // 3. Close open popovers on outside click or Escape
    document.addEventListener('click', (e) => {
      if (langDropdown && !langDropdown.contains(e.target) && e.target !== langBtn) {
        langDropdown.classList.remove('open');
        if (langBtn) langBtn.setAttribute('aria-expanded', 'false');
      }
      if (loginDropdown && !loginDropdown.contains(e.target) && e.target !== loginBtn) {
        loginDropdown.classList.remove('open');
        if (loginBtn) loginBtn.setAttribute('aria-expanded', 'false');
      }
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        if (langDropdown) {
          langDropdown.classList.remove('open');
          if (langBtn) langBtn.setAttribute('aria-expanded', 'false');
        }
        if (loginDropdown) {
          loginDropdown.classList.remove('open');
          if (loginBtn) loginBtn.setAttribute('aria-expanded', 'false');
        }
      }
    });

    // 5. Scroll to top button
    const scrollTopBtn = document.getElementById('scroll-top-btn');
    if (scrollTopBtn) {
      scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }
  }

  // --- Initialize when DOM is ready ---
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      initEvents();
      render();
    });
  } else {
    initEvents();
    render();
  }

  // Expose global controller if needed
  window.KPInfoApp = {
    setLanguage,
    setPage,
    getLanguage: () => currentLang,
    getPage: () => currentPage
  };
})();
