# IQLExchange

Dieses Modul dient zur Abfrage der Urlaubstage von einem Microsoft Exchange Server, zur Zeit werden Microsoft Exchange 2007/2010/2013 unterstützt, Exchange 2016 ist in Vorbereitung.
Im Konfigurationsformular müssen

* Benutzername
* Passwort
* Kalenderbenutzer (email Adresse)
* Exchangeserver-Version
* Exchangeserver-Adresse (FQDN oder IP-Adresse)
* Suchbegriff
* Zeitraum

angegeben werden.

**Befehlsreferenz:**

**IQLEX_Update** dient zum Aktualisieren der Instanz (Sync mit Exchange Server)
```php
<?php
IQLEX_Update( interger $InstanceID );
?>
```

**IQLEX_GetVacation** dient zur Abfrage des Urlaubsstatus aller angelegten Instanzen
```php
<?php
IQLEX_GetVacation( interger $InstanceID, string $user );
?>
```
mögliche Parameter:

| User 		| Ergebnis															|
| :-------: | :---------------------------------------------------------------:	|
| all       | Prüft ob alle angelegten Instanzen den Status "Urlaub" haben.		|
| any       | Prüft ob irgendeine angelegte Instanz den Status "Urlaub" hat.	|

**IQLEX_SendMail** dient zum Versand von Plaintext E-Mails
```php
<?php
IQLEX_SendMail( interger $InstanceID, string $to, string $subject, string $content);
?>
```

**IQLEX_SendMailHTML** dient zum Versand von HTML E-Mails
```php
<?php
IQLEX_SendMailHTML( interger $InstanceID, string $to, string $subject, string $content);
?>
```

