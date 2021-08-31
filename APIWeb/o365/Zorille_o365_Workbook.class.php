<?php

/**
 * Gestion de o365.
 * @author dvargas
 */
namespace Zorille\o365;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class Workbook /me/drive/root:/demo.xlsx:/workbook/tables/Table1/rows/add /users/fbe602e3-75c7-49e2-9def-c764ac03aa5b/drive/root:/testz.xlsx:/workbook/createSession
 * @package Lib
 * @subpackage o365
 */
class Workbook extends Item {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $workbook_name = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $workbook_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $stockage = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $base64_excelfile = "UEsDBBQABgAIAAAAIQCkU8XPTgEAAAgEAAATAAAAW0NvbnRlbnRfVHlwZXNdLnhtbKyTy07DMBBF90j8Q+Qtit2yQAg17YLHErooH2DiSWLVL3nc0v49E/exQKEVajexYs/ccz0znsw21hRriKi9q9iYj1gBrvZKu7Zin4u38pEVmKRT0ngHFdsCstn09may2AbAgrIdVqxLKTwJgXUHViL3ARydND5ameg3tiLIeilbEPej0YOovUvgUpl6DTadvEAjVyYVrxva3jmJYJAVz7vAnlUxGYLRtUzkVKyd+kUp9wROmTkGOx3wjmwwMUjoT/4G7PM+qDRRKyjmMqZ3acmG2Bjx7ePyy/slPy0y4NI3ja5B+XplqQIcQwSpsANI1vC8ciu1O/g+wc/BKPIyvrKR/n5Z+IyPRP0Gkb+XW8gyZ4CYtgbw2mXPoqfI1K959AFpciP8n34YzT67DCQEMWk4DudQk49EmvqLrwv9u1KgBtgiv+PpDwAAAP//AwBQSwMEFAAGAAgAAAAhALVVMCP0AAAATAIAAAsAAABfcmVscy8ucmVsc6ySTU/DMAyG70j8h8j31d2QEEJLd0FIuyFUfoBJ3A+1jaMkG92/JxwQVBqDA0d/vX78ytvdPI3qyCH24jSsixIUOyO2d62Gl/pxdQcqJnKWRnGs4cQRdtX11faZR0p5KHa9jyqruKihS8nfI0bT8USxEM8uVxoJE6UchhY9mYFaxk1Z3mL4rgHVQlPtrYawtzeg6pPPm3/XlqbpDT+IOUzs0pkVyHNiZ9mufMhsIfX5GlVTaDlpsGKecjoieV9kbMDzRJu/E/18LU6cyFIiNBL4Ms9HxyWg9X9atDTxy515xDcJw6vI8MmCix+o3gEAAP//AwBQSwMEFAAEAAgAQXjLRvqonSWzAQAA1wMAABAAAABkb2NQcm9wcy9hcHAueG1snFPBbhshFLz7K1bcY9ZpFVUWS1Q5jXJoVUt2kjNl33pRWEC855XdX+uhn9RfKLvEsdtYbVVO8GYY5g3w49t3cb3rbNFDRONdxWbTkhXgtK+N21Tsfn178Y4VSMrVynoHFdsDsms5EcvoA0QygEVScFixlijMOUfdQqdwmmCXkMbHTlFaxg33TWM03Hi97cARvyzLKw47AldDfRFeBFlWnPf0v6K114M/fFjvQ/IrJ0Ua4n0I1mhFqVP5yejo0TdUfNhpsIKfgpmefK5Ab6OhvSwFP11mwkorC4t0jmyURRD8WMiEO1BDjktlImYPo4+e5j1o8rFA8zVFesmKLwph8FqxXkWjHD17HvjDEMOejByFMpRBG5CifPTxCVsAQsHTjlwcvbxwh/pZpb8eYd7K2SibJv+gOVJzn8/589d5iLUhC/i5WapIf8podprR2NeZhHK/t7A1Njv9LYAzls6cLxa+C8rtpeCHWb7Oj8Y94X1Y+xtFcLjzX4uZuGpVhDo9mAPpWMiEu3TV0Q5yi1a5DdQH4msgb0iv8yH/UTm7mpZvyvQgT2oTwY//Uf4EAAD//wMAUEsDBAoAAAAAAAAAIQD/////OQEAADkBAAAQAAAAW3RyYXNoXS8wMDAwLmRhdP////8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABQSwMEFAAGAAgAAAAhAI2H2nDgAAAALQIAABoAAAB4bC9fcmVscy93b3JrYm9vay54bWwucmVsc6yRy2rDMBBF94X+g5h9PXYKpZTI2ZRCtsX9ACGPH8SWhGaS1n9f4YLdQEg22QiuBt1zJG13P+OgThS5905DkeWgyFlf967V8FV9PL2CYjGuNoN3pGEihl35+LD9pMFIOsRdH1ilFscaOpHwhsi2o9Fw5gO5NGl8HI2kGFsMxh5MS7jJ8xeM/zugPOtU+1pD3NfPoKopJPLtbt80vaV3b48jObmAQJZpSBdQlYktiYa/nCVHwMv4zT3xkp6FVvoccV6Law7FPR2+fTxwRySrx7LFOE8WGTz75PIXAAD//wMAUEsDBBQABgAIAAAAIQCfiOttlgIAAAQGAAANAAAAeGwvc3R5bGVzLnhtbKRUW2vbMBR+H+w/CL27st04S4LtsjQ1FLoxaAd7VWw5EdXFSErnbOy/78iXxKVjG+2Ldc7x0Xe+c1N61UqBnpixXKsMRxchRkyVuuJql+GvD0WwwMg6qioqtGIZPjKLr/L371LrjoLd7xlzCCCUzfDeuWZFiC33TFJ7oRum4E+tjaQOVLMjtjGMVtZfkoLEYTgnknKFe4SVLP8HRFLzeGiCUsuGOr7lgrtjh4WRLFe3O6UN3Qqg2kYzWqI2mpt4jNCZXgSRvDTa6tpdACjRdc1L9pLrkiwJLc9IAPs6pCghYdwnnqe1Vs6iUh+Ug/IDuie9elT6uyr8L2/svfLU/kBPVIAlwiRPSy20QQ6KDbl2FkUl6z2uqeBbw71bTSUXx94ce0PXn8FPcqiWNxLPYzgsXOJCnFjFngAY8hQK7phRBShokB+ODYRXMBs9TOf3D++doccoTiYXSBcwT7faVDCL53qMpjwVrHZA1PDd3p9ON/DdauegZXlacbrTigqfSg9yEiCdkglx7+f1W/0Mu62ROshCutsqwzD5vgijCIkMYo/XKx5/itZjvxkWtfVzfECc0H5G+hQe+X5n+LNfMAGTM0Cg7YELx9UfCANm1Z5LEPoOOL8sXXFOUaASFavpQbiH088Mn+VPrOIHCUs1eH3hT9p1EBk+y3e+U9Hcx2Ctu7MwXnCig+EZ/nmz/rDc3BRxsAjXi2B2yZJgmaw3QTK7Xm82xTKMw+tfk619w852L0yewmKtrIDNNkOyA/n7sy3DE6Wn380o0J5yX8bz8GMShUFxGUbBbE4XwWJ+mQRFEsWb+Wx9kxTJhHvyylciJFE0vhJtlKwcl0xwNfZq7NDUCk0C9S9JkLET5Px8578BAAD//wMAUEsDBBQABAAIAEF4y0Y6gN2XxgcAAGMuAAATAAAAeGwvdGhlbWUvdGhlbWUxLnhtbOxaS4/bNhC+91cIujt+SX4E6wR+btrsJkHspMiRtmmLWUo0RHo3RhCg114KFEiLXgr01kNRIEB7ag/5NwnaFOhf6FCSLVGWbG6y2SToeoG1LXK+GX4zHA5p/vvny4ObT1xqnGKfE+a1zPK1kmlgb8KmxJu3zAejQaFhGlwgb4oo83DLXGFu3rzx2QG6LhzsYgPEPX4dtUxHiMX1YpFP4DHi19gCe9A2Y76LBHz158Wpj84A1qXFSqlUK7qIeKbhIRdQ785mZIKNkYQ0b3xmwGutoU/hmSd4+DRqmVB/KBVhRT6SlH3kCxCmJ+VYLnwaPOcr3qW+cYpoywSbpuxshJ8I06CIC2homaXgZRZV6YNiBiSooUJXTULFIHhlqdiCC0ZSUW2JRsj9+XgzFMuyrVo7C3J6khIPrE49y4Ls1/u1fi0LkoqUOECiyQR8lUNG0lK70+z07CzYTIgNdEplpsW9eq9azodOQWygq/v5bdvyLx86BbGBtvZDDwZdCLl86BTEBtreD21Z9UrXyodOQWyga/uh66V2z6rnQ6cgANqhxDvZD1yya9VuphMzAAB2xugtPeSmbQ3qlSyTtzHkfN+kmtjoQJ8ntDKQix4zf8A8EYsn8hBFgniGWC3wDE0gD3YRJWOfGEdk7kA6WiCPcXhcqpQGpSr8l39W8GnLn9FUwCgBlx5l1GfC9/eZgckGn/hkIVrmF2CImZB5tDQOmXDIJDI0lSYjNQrELeTNkxBvfv72nx+/Mv7+7ac3z7/LsTMNwJMAr3/9+vUfL89lADAaU/3q+xevf3/x6odv/vrluY7+to/GSfkRcTE37uAz4z5zgR4dDvDYf0eIkYOIAoEc0K6jvA/+SkreWSGqJdjBquce+rBgakkeLh8r4x06/lIQHWNvO64iecwY7TBfj+bb0ryEp0dLb65pr79MCt5H6FTL3C7ylNjsLxdQnBAtpV0HK0O9R5En0Bx7WBiyjZ1grEPZI0IU/x6Tic84mwnjETE6iOgRPyJjZZbEKLeICwGz0hoTRKnigeOHRodRLSp7+FQVhbyBqA4BI0wV7x2ipUCultIRcmnS8UdIOFoDHa78SVKwzwUE6RxTZvSnmHMtkLs+sJqI19uwCGhG7DFduaqoL8iJltYjxFhStMdOug5yF1rCQ+I5SeHP+QnMUGTcY0JL/pipOUV+hwBBnn6kPiRYidS3yMcPYJVNjiIOdtmy9HWi7hAzZfoOV3SG8NZyICuJnFIAqgmXeOcvElLlgf0Rlgc6BL6nwkBTtRJC71oStH2il6pupQoBbcH/6fLfQ0vvHoaUo1FkXa3+ciMRp7Kr1X+931q/R9u3ze7mavVvmZ/66q+dQS97zd9e2WUpIHe24fFBfCwgw9LVO1SYEUqHYkXxEc8+V+BQTE0H0CuGX0d/tDkPzmnx5uRw4cDHzPQK1u5AA6PnPgpUGT4TXxLhDB20gAxUTp3GrvVLCZ5pt9LDWDAOBx85IImumQPJHvZaSr6DGXTpHrNpeAhcLssD3ywCkkKRIEciFizZ+oJw2CNCfbW6hpSMlM3w8ocku80T5+MZFs95yKe09fI4PQ81KqdVDXYiZ8Sc1nWkLp7TIHI+BVKbb0NqQw5v37y4AFLDIM7NC3K6wkkp7K1bpm2BSWAULOCI4qnMEll1oZwVUWranjufct5666miJDzYLu537NYca0rus+hOJp0LCIdI8aXlLYXT86wFKqdBXGYEY5KdaGjcQVMcLT5S7AOwermZq3mO/KO4QwbqXna2Wa03NMQuIFQvMXNJVfm1n8yR3s6ykHrGWcusVW1InhO0aJkz+AEKProLyKJcHskhOodrABPh5xReoGNHNbgOc9kr+Hl+f30Z+E3WOXsr1oXPRQ9xJ5wzQf+8RAT6XSKwb1DitkwZB9kBJPmkXubqEFJVrkCddsXV2km7uWpCvXfFVR5XYaxlT0+IVzyb4YnIbg4glS7bIav0gTIqYxGSFiTUbGOoZmy3fwgd2VZElrAlTPKhMz0zxnTp30eQxex6WUbhlHD43bochuSUwI2eTdUYp7jUjlWnhk/eYQnSyj6hyFJEFw6Ktn7a25RQ2Q4GwKEbCrK7xT7PLK3j5iDyVIxU4xYAhMt4Lg8Zdkbt/sSuu1RIe3aggTk7WiM/ZOpShy17rl+AGe8vm/t2+tKC5NGA3OPnFi7BaNaL5LYF+we73tykovjjP3dJMKozFaQv0syG5xgZOW7tuPW7FIwL7aZOSRjpUwt7nc3SHo9uTIIw/liOaxKuOAc1SpBLF+YG+XrMEadJV5xDTHWFTsxcvCve/14pQU5NZ4xrTpMZR2urdAHkSIgdp7gw6y7stEaqyltmZNvmzDzMofJRcDNZvTcMFrHxY6iwenDTbkkFj3IHPIeLvz6CjcowSMZx6RQ2Kate+MhY+qRlPi3ZbatbsbuFUsPuF6yqVSo07Ha10Lbtarlvl0u9TuVZaht1IBy3bIcGDuByCl1FF6iD51uXqN31HZ5rE+YWWXBJuhiMLrhEXa7kX6I2CBRDT2uVQbPa7NQKzWp7ULB6nUah2a11Cr1at94b9Lp2ozl4ZhqnQWerXe1atX6jUCt3uwWrVpJjaTQLdatSaVv1dqNvtZ8lt1ySa2AvJh6+BHxtnHDjPwAAAP//AwBQSwMEFAAEAAgA5DKpTqdpB8f2AQAA1wMAAA8AAAB4bC93b3JrYm9vay54bWysk81u2zAMx+99CkF3R7bjuElgp1iWFAswDMOQtWdFlmMh+jAkeUkw7Ml26CPtFUbbdZa2lx6miyVK/JF/kv7z+ym7OymJfnDrhNE5jkYhRlwzUwi9z/H37X0wxch5qgsqjeY5PnOH7xY32dHYw86YAwJ/7XJceV/PCXGs4oq6kam5hpvSWEU9HO2euNpyWriKc68kicMwJYoKjXvC3L6HYcpSML4yrFFc+x5iuaQesneVqN1AU+w9OEXtoakDZlQNiJ2Qwp87KEaKzTd7bSzdSVB9iiYDGbZv0Eowa5wp/QhQpE/yjd4oJFHUS17cIFhZKSR/6EuPaF1/oaoNJTGS1Pl1ITwvcpzC0Rz5C4Nt6mUjJNxGSRKHmDwDh6Z8tajgJW2k30I7hhjwOk3CKLo8b/v3IPjR9e5dTgOitaPTo9CFOeYYpuJ8tT925kdR+CrHcRyncN/bPnGxrzxEitNkcglEXkXKuim4DtsZkO4qcM9BWwRj147KphWJkZ0L2NhN8S97cg3JGJUMZLefziWNZ9H4kgA/+c/OX8kEA2qsyPHPKAk/3IazJAjX40mQTGdxME3GcfAxWcXrye16tV5Ofv3/5rfFblcG8zQfit4KqKj1W0vZAf7Ab7xcUgdDcVHduRDI/rnj7bZTlpEBsvgLAAD//wMAUEsDBBQABAAIAOmIxUZGcAELfAEAAJ4CAAAYAAAAeGwvd29ya3NoZWV0cy9zaGVldDEueG1sjNLNbtswDADge59C0L2W063rGsQJBgTBeigwbOvutEzbQiTRkJimebYd9kh7hdF2EwzopTdTpj7wR39//1ltXoJXz5iyo1jpRVFqhdFS42JX6aefu+vPWmWG2ICniJU+Ydab9dXqSGmfe0RWAsRc6Z55WBqTbY8BckEDRvnTUgrAEqbO5CEhNNOl4M1NWX4yAVzUs7BM7zGobZ3FLdlDwMgzktADS/m5d0M+a8G+hwuQ9ofh2lIYhKidd3ya0DPzsvgIb6XgbKJMLRdy08w1vW3v3twbsFoFu3zoIiWovQxwEvX6SqlV46SJcfAqYVvpLwttpvNpRL8cHvMYSuLlQDHUP9CjZWxkW1qNa6iJ9mP2gxyVr4S5XJmMWdhNy/iWVIMtHDx/p+NXdF3PQt1Kx2Ovy+a0xWxluIIVN7f/l7QFhrnCATp8hNS5mJXHdsq90yrNWFnIN9MwCncC18RM4Rz18ghQll0WH7RqifgcCL0yl3e1/gcAAP//AwBQSwMEFAAGAAgAAAAhAKRG1zlJAQAAZgIAABEAAABkb2NQcm9wcy9jb3JlLnhtbJSSzW6DMBCE75X6Dsh3ME6aqEFApDbNqZEqNf1Rb5a9IVaxsWynhLevgYQSKZcevTv77czK6fIoy+AHjBWVyhCJYhSAYhUXqsjQ23Yd3qPAOqo4LSsFGWrAomV+e5MynbDKwIupNBgnwAaepGzCdIb2zukEY8v2IKmNvEL55q4ykjr/NAXWlH3TAvAkjudYgqOcOopbYKgHIjohORuQ+mDKDsAZhhIkKGcxiQj+0zow0l4d6DojpRSu0T7Tye6YzVnfHNRHKwZhXddRPe1seP8Ef26eX7uooVDtrRigPOUsYQaoq0y+olKACt6pKagNng6sbDikeKRor1lS6zb+8DsB/KHJU3ylxlmXoScDD7yrpM9w7nxMH1fbNconMZmF8TyMZ1tyn5BFMr37aldezLcu+4I8Lf4XcTEingG978ufkf8CAAD//wMAUEsBAi0AFAAGAAgAAAAhAKRTxc9OAQAACAQAABMAAAAAAAAAAAAAAAAAAAAAAFtDb250ZW50X1R5cGVzXS54bWxQSwECLQAUAAYACAAAACEAtVUwI/QAAABMAgAACwAAAAAAAAAAAAAAAAB/AQAAX3JlbHMvLnJlbHNQSwECLQAUAAQACAAAACEA+qidJbMBAADXAwAAEAAAAAAAAAAAAAAAAACcAgAAZG9jUHJvcHMvYXBwLnhtbFBLAQItAAoAAAAAAAAAIQD/////OQEAADkBAAAQAAAAAAAAAAAAAAAAAH0EAABbdHJhc2hdLzAwMDAuZGF0UEsBAi0AFAAGAAgAAAAhAI2H2nDgAAAALQIAABoAAAAAAAAAAAAAAAAA5AUAAHhsL19yZWxzL3dvcmtib29rLnhtbC5yZWxzUEsBAi0AFAAGAAgAAAAhAJ+I622WAgAABAYAAA0AAAAAAAAAAAAAAAAA/AYAAHhsL3N0eWxlcy54bWxQSwECLQAUAAQACAAAACEAOoDdl8YHAABjLgAAEwAAAAAAAAAAAAAAAAC9CQAAeGwvdGhlbWUvdGhlbWUxLnhtbFBLAQItABQABAAIAAAAIQCnaQfH9gEAANcDAAAPAAAAAAAAAAAAAAAAALQRAAB4bC93b3JrYm9vay54bWxQSwECLQAUAAQACAAAACEARnABC3wBAACeAgAAGAAAAAAAAAAAAAAAAADXEwAAeGwvd29ya3NoZWV0cy9zaGVldDEueG1sUEsBAi0AFAAGAAgAAAAhAKRG1zlJAQAAZgIAABEAAAAAAAAAAAAAAAAAiRUAAGRvY1Byb3BzL2NvcmUueG1sUEsFBgAAAAAKAAoAfAIAAAEXAAAAAA==";

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Workbook. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice Reference sur un objet webservice
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Workbook
	 */
	static function &creer_Workbook(
			&$liste_option,
			&$webservice,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Workbook ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Workbook
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 * @return true
	 */
	public function __construct(
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * ******************************* WORKBOOK *********************************
	 */
	public function creer_un_workbook(
			$lieu_de_stockage,
			$nom_excel,
			$params = array ()) {
		$this->onDebug ( "Lieu : " . $lieu_de_stockage . " nom: " . $nom_excel, 0 );
		$workbook = $this->setDrive ( $lieu_de_stockage )
			->setWorkbookName ( $nom_excel )
			->workbook_create ( $params );
		$this->onDebug ( $workbook, 2 );
		$this->setWsReponse ( $workbook );
		if (isset ( $workbook->id )) {
			return $this->setWorkbookId ( $workbook->id );
		}
		return $this->onError ( "Aucun classeur avec l'adresse : " . $lieu_de_stockage . " n'a ete cree", $workbook, 1 );
	}

	/**
	 * Verifie qu'un classeur id est remplit/existe
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_workbookid() {
		if (empty ( $this->getWorkbookId () )) {
			$this->onError ( "Il faut un classeur id renvoye par O365 pour travailler" );
			return false;
		}
		return true;
	}

	/**
	 * ******************************* WORKBOOK URI ******************************
	 */
	public function workbook_uri() {
		if ($this->valide_itemid ( false )) {
			return $this->getDrive () . "/items/" . $this->getItemId () . '/workbook';
		}
		if (! empty ( $this->getWorkbookName () )) {
			return $this->getDrive () . ":/" . $this->getWorkbookName () . ':/workbook';
		}
		return $this->onError ( "Pas de reference sur le classeur" );
	}

	public function workbook_create_uri() {
		return $this->workbook_uri () . '/createSession';
	}

	public function workbook_worksheets_uri() {
		return $this->workbook_uri () . '/worksheets';
	}

	/**
	 * ******************************* O365 WORKBOOK *********************************
	 */
	public function prepare_header() {
		if ($this->valide_workbookid ()) {
			$this->getObjetO365Wsclient ()
				->setAjoutHeader ( "workbook-session-id: " . $this->getWorkbookId () );
		}
		return $this;
	}

	public function nettoie_header() {
		$this->getObjetO365Wsclient ()
			->setAjoutHeader ( "" );
		return $this;
	}

	public function workbook_create(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		if (empty ( $this->getDrive () )) {
			return $this->onError ( "Il faut un lieu pour deposer le fichier excel" );
		}
		if (! isset ( $params ["persistChanges"] )) {
			$params ["persistChanges"] = true;
		}
		//on creer le fichier avant de l'ouvrir
		$this->getObjetO365Wsclient ()
			->putContentMethod ( $this->getDrive () . ':/' . $this->getWorkbookName () . ':/content', base64_decode ( $this->getBase64ExcelFile () ) );
		//On l'ouvre
		return $this->getObjetO365Wsclient ()
			->jsonPostMethod ( $this->workbook_create_uri (), $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getWorkbookName() {
		return $this->workbook_name;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setWorkbookName(
			$workbook_name) {
		$this->workbook_name = $workbook_name;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getWorkbookId() {
		return $this->workbook_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setWorkbookId(
			$workbook_id) {
		$this->workbook_id = $workbook_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDrive() {
		return $this->stockage;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setDrive(
			$stockage) {
		$this->stockage = $stockage;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getBase64ExcelFile() {
		return $this->base64_excelfile;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Workbook :";
		return $help;
	}
}
?>
