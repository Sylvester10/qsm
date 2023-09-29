$(document).ready(function(){
	
	
	//Function To List out the LGAs based on state selected
	function get_lgas(state){
		$("#lga").empty(); //Reset LGAs
		$("#lga").append('<option value="">Select</option>'); //default selected 
		$(state).each(function(i) {
			$("#lga").append("<option value=\""+state[i].value+"\">"+state[i].display+"</option>")
		});
	}
	
	

	//Initializing arrays with lga names

	var Abuja = 
	[
		{display: "Abaji", value: "Abaji"},
		{display: "Abuja Municipal", value: "Abuja Municipal"},	
		{display: "Bwari", value: "Bwari"}, 
		{display: "Gwagwalada", value: "Gwagwalada"},
		{display: "Kuje", value: "Kuje"},
		{display: "Kwali", value: "Kwali"}
	];

	var Abia = 
	[
		{display: "Aba South", value: "Aba South"},
		{display: "Aba North", value: "Aba North"},	
		{display: "Arochukwu", value: "Arochukwu"}, 
		{display: "Bende", value: "Bende"},
		{display: "Ikwuano", value: "Ikwuano"},
		{display: "Isiala Ngwa North", value: "Isiala Ngwa North"},
		{display: "Isiala Ngwa South", value: "Isiala Ngwa South"},
		{display: "Isiukwuato", value: "Isiukwuato"}, 
		{display: "Obi Ngwa", value: "Obi Ngwa"}, 
		{display: "Ohafia", value: "Ohafia"},
		{display: "Osisioma Ngwa", value: "Osisioma Ngwa"},
		{display: "Ugwunagbo", value: "Ugwunagbo"},
		{display: "Ukwa East", value: "Ukwa East"},
		{display: "Ukwa West", value: "Ukwa West"},
		{display: "Umu Nneochi", value: "Umu Nneochi"}, 
		{display: "Umuahia North", value: "Umuahia North"}, 
		{display: "Umuahia South", value: "Umuahia South"}
	];

	var Adamawa = 
	[
		{display: "Demsa", value: "Demsa"},
		{display: "Fufore", value: "Fufore"},	
		{display: "Ganye", value: "Ganye"}, 
		{display: "Girei", value: "Girei"},
		{display: "Gombi", value: "Gombi"},
		{display: "Hung", value: "Hung"},
		{display: "Jada", value: "Jada"},
		{display: "Lamurde", value: "Lamurde"}, 
		{display: "Madagali", value: "Madagali"}, 
		{display: "Maiha", value: "Maiha"},
		{display: "Mayo-Belwa", value: "Mayo-Belwa"},
		{display: "Michika", value: "Michika"},
		{display: "Mubi South", value: "Mubi South"},
		{display: "Numna", value: "Numna"},
		{display: "Shelleng", value: "Shelleng"}, 
		{display: "Song", value: "Song"}, 
		{display: "Toungo", value: "Toungo"},
		{display: "Jimeta", value: "Jimeta"},
		{display: "Yola North", value: "Yola North"},
		{display: "Yola South", value: "Yola South"}
	];

	var Akwa_Ibom = 
	[
		{display: "Abak", value: "Abak"},
		{display: "eastern Obolo", value: "eastern Obolo"},	
		{display: "Eket", value: "Eket"}, 
		{display: "Essien Udim", value: "Essien Udim"},
		{display: "Etimekpo", value: "Etimekpo"},
		{display: "Etinan", value: "Etinan"},
		{display: "Ibeno", value: "Ibeno"},
		{display: "Ibesikpo Asutan", value: "Ibesikpo Asutan"}, 
		{display: "Ibiono Ibom", value: "Ibiono Ibom"}, 
		{display: "Ika", value: "Ika"},
		{display: "Ikono", value: "Ikono"},
		{display: "Ikot Abasi", value: "Ikot Abasi"},
		{display: "Ikot Ekpene", value: "Ikot Ekpene"},
		{display: "Ini", value: "Ini"},
		{display: "Itu", value: "Itu"}, 
		{display: "Mbo", value: "Mbo"}, 
		{display: "Mkpat Enin", value: "Mkpat Enin"},
		{display: "Nsit Ibom", value: "Nsit Ibom"},
		{display: "Nsit Ubium", value: "Nsit Ubium"}, 
		{display: "Obot Akara", value: "Obot Akara"}, 
		{display: "Okobo", value: "Okobo"},
		{display: "Onna", value: "Onna"}, 
		{display: "Orukanam", value: "Orukanam"},
		{display: "Oron", value: "Oron"},
		{display: "Udung Uko", value: "Udung Uko"}, 
		{display: "Ukanafun", value: "Ukanafun"}, 
		{display: "Esit Eket", value: "Esit Eket"},
		{display: "Uruan", value: "Uruan"},
		{display: "Urue Offoung", value: "Urue Offoung"},
		{display: "Oruko Ete", value: "Oruko Ete"},
		{display: "Uyo", value: "Uyo"}
	];

	var Anambra = 
	[
		{display: "Aguata", value: "Aguata"},
		{display: "Anambra East", value: "Anambra East"},	
		{display: "Anambra West", value: "Anambra West"}, 
		{display: "Anaocha", value: "Anaocha"},
		{display: "Awka North", value: "Awka North"},
		{display: "Awka South", value: "Awka South"},
		{display: "Ayamelum", value: "Ayamelum"},
		{display: "Ekwusigo", value: "Ekwusigo"}, 
		{display: "Dunukofia", value: "Dunukofia"}, 
		{display: "Idemili North", value: "Idemili North"},
		{display: "Idemili South", value: "Idemili South"},
		{display: "Ihiala", value: "Ihiala"},
		{display: "Njikoka", value: "Njikoka"},
		{display: "Nnewi North", value: "Nnewi North"},
		{display: "Nnewi South", value: "Nnewi South"}, 
		{display: "Ogbaru", value: "Ogbaru"}, 
		{display: "Onitsha North", value: "Onitsha North"},
		{display: "Onitsha South", value: "Onitsha South"},
		{display: "Orumba North", value: "Orumba North"}, 
		{display: "Orumba South", value: "Orumba South"}, 
		{display: "Oyi", value: "Oyi"}
	];

	var Bauchi = 
	[
		{display: "Alkaleri", value: "Alkaleri"},
		{display: "Bauchi", value: "Bauchi"},	
		{display: "Bogoro", value: "Bogoro"}, 
		{display: "Darazo", value: "Darazo"},
		{display: "Dass", value: "Dass"},
		{display: "Gamawa", value: "Gamawa"},
		{display: "Ganjuwa", value: "Ganjuwa"},
		{display: "Giade", value: "Giade"}, 
		{display: "Jama`are", value: "Jama`are"}, 
		{display: "Katagum", value: "Katagum"},
		{display: "Kirfi", value: "Kirfi"},
		{display: "Misau", value: "Misau"},
		{display: "Njikoka", value: "Njikoka"},
		{display: "Ningi", value: "Ningi"},
		{display: "hira", value: "hira"}, 
		{display: "Tafawa Balewa", value: "Tafawa Balewa"}, 
		{display: "Itas gadau", value: "Itas gadau"},
		{display: "Toro", value: "Toro"},
		{display: "Warji", value: "Warji"}, 
		{display: "Zaki", value: "Zaki"}, 
		{display: "Dambam", value: "Dambam"}
	];

	var Bayelsa = 
	[
		{display: "Brass", value: "Brass"},
		{display: "Ekeremor", value: "Ekeremor"},	
		{display: "Kolok/Opokuma", value: "Kolok/Opokuma"}, 
		{display: "Nembe", value: "Nembe"},
		{display: "Ogbia", value: "Ogbia"},
		{display: "Sagbama", value: "Sagbama"},
		{display: "Southern Ijaw", value: "Southern Ijaw"},
		{display: "Yenagoa", value: "Yenagoa"}, 
		{display: "Membe", value: "Membe"}
	];

	var Benue = 
	[
		{display: "Ador", value: "Ador"},
		{display: "Agatu", value: "Agatu"},	
		{display: "Apa", value: "Apa"}, 
		{display: "Buruku", value: "Buruku"},
		{display: "Gboko", value: "Gboko"},
		{display: "Guma", value: "Guma"},
		{display: "Gwer east", value: "Gwer east"},
		{display: "Gwer west", value: "Gwer west"}, 
		{display: "Kastina", value: "Kastina"},
		{display: "Konshisha", value: "Konshisha"},
		{display: "Kwande", value: "Kwande"},
		{display: "Logo", value: "Logo"},
		{display: "Obi", value: "Obi"},
		{display: "Ogbadibo", value: "Ogbadibo"},
		{display: "Ohimini", value: "Ohimini"},
		{display: "Oju", value: "Oju"},
		{display: "Okpokwu", value: "Okpokwu"},
		{display: "Oturkpo", value: "Oturkpo"},
		{display: "Tarka", value: "Tarka"},
		{display: "Ukum", value: "Ukum"},
		{display: "Vandekya", value: "Vandekya"}
	];

	var Borno = 
	[
		{display: "Abadan", value: "Abadan"},
		{display: "Askira/Uba", value: "Askira/Uba"},	
		{display: "Bama", value: "Bama"}, 
		{display: "Bayo", value: "Bayo"},
		{display: "Biu", value: "Biu"},
		{display: "Chibok", value: "Chibok"},
		{display: "Damboa", value: "Damboa"},
		{display: "Dikwagubio", value: "Dikwagubio"}, 
		{display: "Guzamala", value: "Guzamala"},
		{display: "Konshisha", value: "Konshisha"},
		{display: "Gwoza", value: "Gwoza"},
		{display: "Hawul", value: "Hawul"},
		{display: "Jere", value: "Jere"},
		{display: "Kaga", value: "Kaga"},
		{display: "Kalka/Balge", value: "Kalka/Balge"},
		{display: "Konduga", value: "Konduga"},
		{display: "Kukawa", value: "Kukawa"},
		{display: "Kwaya-ku", value: "Kwaya-ku"},
		{display: "Mafa", value: "Mafa"},
		{display: "Magumeri", value: "Magumeri"},
		{display: "Maiduguri", value: "Maiduguri"},
		{display: "Marte", value: "Marte"},
		{display: "Mobbar", value: "Mobbar"},
		{display: "Monguno", value: "Monguno"},
		{display: "Ngala", value: "Ngala"},
		{display: "Nganzai", value: "Nganzai"},
		{display: "Shani", value: "Shani"}
	];

	var Cross_River = 
	[
		{display: "Abia", value: "Abia"},
		{display: "Akampa", value: "Akampa"},	
		{display: "Akpabuyo", value: "Akpabuyo"}, 
		{display: "Bakassi", value: "Bakassi"},
		{display: "Bekwara", value: "Bekwara"},
		{display: "Biase", value: "Biase"},
		{display: "Boki", value: "Boki"},
		{display: "Calabar south", value: "Calabar south"}, 
		{display: "Etung", value: "Etung"},
		{display: "Ikom", value: "Ikom"},
		{display: "Obanliku", value: "Obanliku"},
		{display: "Obubra", value: "Obubra"},
		{display: "Obudu", value: "Obudu"},
		{display: "Odukpani", value: "Odukpani"},
		{display: "Ogoja", value: "Ogoja"},
		{display: "Yala", value: "Yala"},
		{display: "Yarkur", value: "Yarkur"}
	];

	var Delta = 
	[
		{display: "Aniocha south", value: "Aniocha south"},
		{display: "Anioha", value: "Anioha"},	
		{display: "Bomadi", value: "Bomadi"}, 
		{display: "Burutu", value: "Burutu"},
		{display: "Ethiope west", value: "Ethiope west"},
		{display: "Ethiope east", value: "Ethiope east"},
		{display: "Ika south", value: "Ika south"},
		{display: "Ika north east", value: "Ika north east"}, 
		{display: "Isoko south", value: "Isoko south"},
		{display: "Isoko north", value: "Isoko north"},
		{display: "Ndokwa east", value: "Ndokwa east"},
		{display: "Ndokwa west", value: "Ndokwa west"},
		{display: "Okpe", value: "Okpe"},
		{display: "Oshimili north", value: "Oshimili north"},
		{display: "Oshimili south", value: "Oshimili south"},
		{display: "Patani", value: "Patani"},
		{display: "Sapele", value: "Sapele"},
		{display: "Udu", value: "Udu"},
		{display: "Ughelli south", value: "Ughelli south"},
		{display: "Ughelli north", value: "Ughelli north"},
		{display: "Ukwuani", value: "Ukwuani"},
		{display: "Uviwie", value: "Uviwie"},
		{display: "Warri central", value: "Warri central"},
		{display: " Warri north", value: " Warri north"},
		{display: "Warri south", value: "Warri south"}
	];

	var Ebonyi = 
	[
		{display: "Abakaliki", value: "Abakaliki"},
		{display: "Afikpo North", value: "Afikpo North"},	
		{display: "Afikpo South", value: "Afikpo South"}, 
		{display: "Ebonyi", value: "Ebonyi"},
		{display: "Ezza North", value: "Ezza North"},
		{display: "Ezza South", value: "Ezza South"},
		{display: "Ikwo", value: "Ikwo"},
		{display: "Ishielu", value: "Ishielu"}, 
		{display: "Ivo", value: "Ivo"}, 
		{display: "Izzi", value: "Izzi"},
		{display: "Ohaozara", value: "Ohaozara"},
		{display: "Ohaukwu", value: "Ohaukwu"},
		{display: "Onicha", value: "Onicha"}
	];

	var Edo = 
	[
		{display: "Akoko-Edo", value: "Akoko-Edo"},
		{display: "Egor", value: "Egor"},	
		{display: "Esan east", value: "Esan east"}, 
		{display: "Esan south", value: "Esan south"},
		{display: "Esan central", value: "Esan central"},
		{display: "Esan west", value: "Esan west"},
		{display: "Etsako central", value: "Etsako central"},
		{display: "Etsako east", value: "Etsako east"}, 
		{display: "Etsako", value: "Etsako"}, 
		{display: "Orhionwon", value: "Orhionwon"},
		{display: "Ivia north", value: "Ivia north"},
		{display: "Ovia south west", value: "Ovia south west"},
		{display: "Owan west", value: "Owan west"},
		{display: "Owan south", value: "Owan south"},
		{display: "Uhunwonde", value: "Uhunwonde"},
		{display: "Onicha", value: "Onicha"}
	];

	var Ekiti = 
	[
		{display: "Ado Ekiti", value: "Ado Ekiti"},
		{display: "Aiyekire", value: "Aiyekire"},
		{display: "Effon Alaiye", value: "Effon Alaiye"},	
		{display: "Ekiti south west", value: "Ekiti south west"}, 
		{display: "Ekiti west", value: "BakaEkiti westssi"},
		{display: "Ekiti east", value: "Ekiti east"},
		{display: "Emure/ise", value: "Emure/ise"},
		{display: "Orun", value: "Orun"},
		{display: "Ido", value: "Ido"}, 
		{display: "Osi", value: "Osi"},
		{display: "Ijero", value: "Ijero"},
		{display: "Ikere", value: "Ikere"},
		{display: "Ikole", value: "Ikole"},
		{display: "Ilejemeje", value: "Ilejemeje"},
		{display: "Irepodun", value: "Irepodun"},
		{display: "Ise/Orun", value: "Ise/Orun"},
		{display: "Moba", value: "Moba"},
		{display: "Oye", value: "Oye"}
	];
	
	var Enugu = 
	[
		{display: "Aninri", value: "Aninri"},
		{display: "Awgu", value: "Awgu"},	
		{display: "Edem", value: "Edem"}, 
		{display: "Enugu East", value: "Enugu East"},
		{display: "Enugu South", value: "Enugu South"},
		{display: "Ezeagu", value: "Ezeagu"},
		{display: "Igbo Etiti", value: "Igbo Etiti"},
		{display: "Igbo Eze North", value: "Igboeze North"}, 
		{display: "Igbo Eze South", value: "Igboeze South"}, 
		{display: "Isi Uzo", value: "Isi Uzo"},
		{display: "Nkanu East", value: "Nkanu East"},
		{display: "Nkanu West", value: "Nkanu West"},
		{display: "Nsukka", value: "Nsukka"},
		{display: "Oji River", value: "Oji River"},
		{display: "Udenu", value: "Udenu"}, 
		{display: "Udi", value: "Udi"}, 
		{display: "Unadu", value: "Unadu"},
		{display: "Uzo Uwani", value: "Uzo Uwani"}
	];

	var Gombe = 
	[
		{display: "Akko", value: "Akko"},
		{display: "Balanga", value: "Balanga"},
		{display: "Billiri", value: "Billiri"},	
		{display: "Dukku", value: "Dukku"}, 
		{display: "Dunakaye", value: "Dunakaye"},
		{display: "Gombe", value: "Gombe"},
		{display: "Kaltungo", value: "Kaltungo"},
		{display: "Kwami", value: "Kwami"},
		{display: "Nafada/Bajoga", value: "Nafada/Bajoga"}, 
		{display: "Shomgom", value: "Shomgom"},
		{display: "Yamaltu/Deba", value: "Yamaltu/Deba"}
	];

	var Imo = 
	[
		{display: "Aboh Mbaise", value: "Aboh Mbaise"},
		{display: "Ahiazu Mbaise", value: "Ahiazu Mbaise"},	
		{display: "Ehime Mbano", value: "Ehime Mbano"}, 
		{display: "Ezinihitte Mbaise", value: "Ezinihitte"},
		{display: "Ideato North", value: "Ideato North"},
		{display: "Ideato South", value: "Ideato South"},
		{display: "Ihitte/Uboma", value: "Ihitte Uboma"},
		{display: "Ikeduru", value: "Ikeduru"}, 
		{display: "Isiala Mbano", value: "Isiala Mbano"}, 
		{display: "Isu", value: "Isu"},
		{display: "Mbaitoli", value: "Mbaitoli"},
		{display: "Ngor Okpala", value: "Ngor Okpala"},
		{display: "Njaba", value: "Njaba"},
		{display: "Nkwerre", value: "Nkwerre"},
		{display: "Nwangele", value: "Nwangele"}, 
		{display: "Obowo", value: "Obowo"}, 
		{display: "Oguta", value: "Oguta"},
		{display: "Ohaji/Egbema", value: "Ohaji Egbama"},
		{display: "Okigwe", value: "Okigwe"}, 
		{display: "Onuimo", value: "Onuimo"}, 
		{display: "Orlu", value: "Orlu"},
		{display: "Orsu", value: "Orsu"}, 
		{display: "Oru East", value: "Oru East"},
		{display: "Oru West", value: "Oru West"},
		{display: "Owerri Municipal", value: "Oweri Municipal"}, 
		{display: "Owerri North ", value: "Owerri North"}, 
		{display: "Owerri West", value: "Owerri West"}
	];

	var Jigawa = 
	[
		{display: "Auyo", value: "Auyo"},
		{display: "Babura", value: "Babura"},
		{display: "Birnin- Kudu", value: "Birnin- Kudu"},	
		{display: "Birniwa", value: "Birniwa"}, 
		{display: "Buji", value: "Buji"},
		{display: "Dute", value: "Dute"},
		{display: "Garki", value: "Garki"},
		{display: "Gagarawa", value: "Gagarawa"},
		{display: "Gumel", value: "Gumel"}, 
		{display: "Guri", value: "Guri"},
		{display: "Gwaram", value: "Gwaram"},
		{display: "Gwiwa", value: "Gwiwa"},
		{display: "Hadeji", value: "Hadeji"},
		{display: "Jahun", value: "Jahun"},
		{display: "Kafin-Hausa", value: "Kafin-Hausa"},
		{display: "kaugama", value: "kaugama"},
		{display: "Kazaure", value: "Kazaure"},
		{display: "Kirikisamma", value: "Kirikisamma"},
		{display: "Birnin-magaji", value: "Birnin-magaji"},
		{display: "Maigatari", value: "Maigatari"},
		{display: "Malamaduri", value: "Malamaduri"},
		{display: "Miga", value: "Miga"},
		{display: "Ringim", value: "Ringim"},
		{display: "Roni", value: "Roni"},
		{display: "Sule Tankarka", value: "Sule Tankarka"},
		{display: "Taura", value: "Taura"},
		{display: "Yankwasi", value: "Yankwasi"}
	];

	var Kaduna = 
	[
		{display: "Brnin Gwari", value: "Brnin Gwari"},
		{display: "Chukun", value: "Chukun"},
		{display: "Giwa", value: "Giwa"},	
		{display: "Kajuru", value: "Kajuru"}, 
		{display: "Igabi", value: "Igabi"},
		{display: "Ikara", value: "Ikara"},
		{display: "Jaba", value: "Jaba"},
		{display: "Jema`a", value: "Jema`a"},
		{display: "Kachia", value: "Kachia"}, 
		{display: "Kaduna North", value: "Kaduna North"},
		{display: "Kaduna south", value: "Kaduna south"},
		{display: "Kagarok", value: "Kagarok"},
		{display: "Kauru", value: "Kauru"},
		{display: "Kabau", value: "Kabau"},
		{display: "Kudan", value: "Kudan"},
		{display: "Kere", value: "Kere"},
		{display: "Makarfi", value: "Makarfi"},
		{display: "Sabongari", value: "Sabongari"},
		{display: "Sanga", value: "Sanga"},
		{display: "Soba", value: "Soba"},
		{display: "Zangon-Kataf", value: "Zangon-Kataf"},
		{display: "Zaria", value: "Zaria"}
	];

	var Kano = 
	[
		{display: "Ajigi", value: "Ajigi"},
		{display: "Albasu", value: "Albasu"},
		{display: "Bagwai", value: "Bagwai"},	
		{display: "Bebeji", value: "Bebeji"}, 
		{display: "Bichi", value: "Bichi"},
		{display: "Bunkure", value: "Bunkure"},
		{display: "Dala", value: "Dala"},
		{display: "Dambatta", value: "Dambatta"},
		{display: "Dawakin kudu", value: "Dawakin kudu"}, 
		{display: "Dawakin tofa", value: "Dawakin tofa"},
		{display: "doguwa", value: "doguwa"},
		{display: "Fagge", value: "Fagge"},
		{display: "Gabasawa", value: "Gabasawa"},
		{display: "Garko", value: "Garko"},
		{display: "Garun mallam", value: "Garun mallam"},
		{display: "Gaya", value: "Gaya"},
		{display: "Gezawa", value: "Gezawa"},
		{display: "Gwale", value: "Gwale"},
		{display: "Gwarzo", value: "Gwarzo"},
		{display: "Kano", value: "Kano"},
		{display: "Karay", value: "Karay"},
		{display: "Kibiya", value: "Kibiya"},
		{display: "Kiru", value: "Kiru"},
		{display: "Kumbtso", value: "Kumbtso"},
		{display: "Kunch", value: "Kunch"},
		{display: "Kura", value: "Kura"},
		{display: "Maidobi", value: "Maidobi"},
		{display: "Makoda", value: "Makoda"},
		{display: "MInjibir Nassarawa", value: "MInjibir Nassarawa"},
		{display: "Rano", value: "Rano"},
		{display: "Rimin gado", value: "Rimin gado"},
		{display: "Rogo", value: "Rogo"},
		{display: "Shanono", value: "Shanono"},
		{display: "Sumaila", value: "Sumaila"},
		{display: "Takai", value: "Takai"},
		{display: "Tarauni", value: "Tarauni"},
		{display: "Tofa", value: "Tofa"},
		{display: "Tsanyawa", value: "Tsanyawa"},
		{display: "Tudunwada", value: "Tudunwada"},
		{display: "Ungogo", value: "Ungogo"},
		{display: "Warawa", value: "Warawa"},
		{display: "Wudil", value: "Wudil"}
	];

	var Katsina = 
	[
		{display: "Bakori", value: "Bakori"},
		{display: "Batagarawa", value: "Batagarawa"},	
		{display: "Batsari", value: "Batsari"}, 
		{display: "Baure", value: "Baure"},
		{display: "Bindawa", value: "Bindawa"},
		{display: "Charanchi", value: "Charanchi"},
		{display: "Dan- Musa", value: "Dan- Musa"},
		{display: "Dandume", value: "Dandume"},
		{display: "Danja", value: "Danja"},
		{display: "Daura", value: "Daura"},
		{display: "Dutsi", value: "Dutsi"},
		{display: "Dutsin `ma", value: "Dutsin `ma"},
		{display: "Faskar", value: "Faskar"},
		{display: "Funtua", value: "Funtua"},
		{display: "Ingawa", value: "Ingawa"},
		{display: "Jibiya", value: "Jibiya"},
		{display: "Kafur", value: "Kafur"},
		{display: "Kaita", value: "Kaita"},
		{display: "Kankara", value: "Kankara"},
		{display: "Kankiya", value: "Kankiya"},
		{display: "Katsina", value: "Katsina"},
		{display: "Furfi", value: "Furfi"},
		{display: "Kusada", value: "Kusada"},
		{display: "Mai aduwa", value: "Mai aduwa"},
		{display: "Malumfashi", value: "Malumfashi"},
		{display: "Mani", value: "Mani"},
		{display: "Mash", value: "Mash"},
		{display: "Matazu", value: "Matazu"},
		{display: "Musawa", value: "Musawa"},
		{display: "Rimi", value: "Rimi"},
		{display: "Sabuwa", value: "Sabuwa"},
		{display: "Safana", value: "Safana"},
		{display: "Sandamu", value: "Sandamu"},
		{display: "Zango", value: "Zango"}
	];

	var Kebbi = 
	[
		{display: "Aliero", value: "Aliero"},
		{display: "Arewa Dandi", value: "Arewa Dandi"},	
		{display: "Argungu", value: "Argungu"}, 
		{display: "Augie", value: "Augie"},
		{display: "Bagudo", value: "Bagudo"},
		{display: "Birnin Kebbi", value: "Birnin Kebbi"},
		{display: "Bunza", value: "Bunza"},
		{display: "Dandi", value: "Dandi"},
		{display: "Danko", value: "Danko"},
		{display: "Fakai", value: "Fakai"},
		{display: "Gwandu", value: "Gwandu"},
		{display: "Jeda", value: "Jeda"},
		{display: "Kalgo", value: "Kalgo"},
		{display: "Koko-besse", value: "Koko-besse"},
		{display: "Maiyaama", value: "Maiyaama"},
		{display: "Ngaski", value: "Ngaski"},
		{display: "Sakaba", value: "Sakaba"},
		{display: "Shanga", value: "Shanga"},
		{display: "Suru", value: "Suru"},
		{display: "Wasugu", value: "Wasugu"},
		{display: "Yauri", value: "Yauri"},
		{display: "Zuru", value: "Zuru"}
	];

	var Kogi = 
	[
		{display: "Adavi", value: "Adavi"},
		{display: "Ajaokuta", value: "Ajaokuta"},	
		{display: "Ankpa", value: "Ankpa"}, 
		{display: "Bassa", value: "Bassa"},
		{display: "Dekina", value: "Dekina"},
		{display: "Yagba east", value: "Yagba east"},
		{display: "Ibaji", value: "Ibaji"},
		{display: "Idah", value: "Idah"},
		{display: "Igalamela", value: "Igalamela"},
		{display: "Ijumu", value: "Ijumu"},
		{display: "Kabba bunu", value: "Kabba bunu"},
		{display: "Kogi", value: "Kogi"},
		{display: "Mopa muro", value: "Mopa muro"},
		{display: "Ofu", value: "Ofu"},
		{display: "Ogori magongo", value: "Ogori magongo"},
		{display: "Okehi", value: "Okehi"},
		{display: "Okene", value: "Okene"},
		{display: "Olamaboro", value: "Olamaboro"},
		{display: "Omala", value: "Omala"},
		{display: "Yagba west", value: "Yagba west"}
	];

	var Kwara = 
	[
		{display: "Asa", value: "Asa"},
		{display: "Baruten", value: "Baruten"},	
		{display: "Ede", value: "Ede"}, 
		{display: "Ekiti", value: "Ekiti"},
		{display: "Ifelodun", value: "Ifelodun"},
		{display: "Ilorin south", value: "Ilorin south"},
		{display: "Ilorin west", value: "Ilorin west"},
		{display: "Ilorin east", value: "Ilorin east"},
		{display: "Irepodun", value: "Irepodun"},
		{display: "Isin", value: "Isin"},
		{display: "Kaiama", value: "Kaiama"},
		{display: "Moro", value: "Moro"},
		{display: "Offa", value: "Offa"},
		{display: "Oke ero", value: "Oke ero"},
		{display: "Oyun", value: "Oyun"},
		{display: "Pategi", value: "Pategi"}
	];

	var Lagos = 
	[
		{display: "Agege", value: "Agege"},
		{display: "Alimosho Ifelodun", value: "Alimosho Ifelodun"},	
		{display: "Alimosho", value: "Alimosho"}, 
		{display: "Amuwo-Odofin", value: "Amuwo-Odofin"},
		{display: "Apapa", value: "Apapa"},
		{display: "Badagry", value: "Badagry"},
		{display: "Epe", value: "Epe"},
		{display: "Eti-Osa", value: "Eti-Osa"},
		{display: "Ibeju- Lekki", value: "Ibeju- Lekki"},
		{display: "Ifako/Ijaye", value: "Ifako/Ijaye"},
		{display: "Ikeja", value: "Ikeja"},
		{display: "Ikorodu", value: "Ikorodu"},
		{display: "Kosofe", value: "Kosofe"},
		{display: "Lagos Island", value: "Lagos Island"},
		{display: "Lagos Mainland", value: "Lagos Mainland"},
		{display: "Mushin", value: "Mushin"},
		{display: "Ojo", value: "Ojo"},
		{display: "Oshodi-Isolo", value: "Oshodi-Isolo"},
		{display: "Shomolu", value: "Shomolu"},
		{display: "Surulere", value: "Surulere"}
	];

	var Nassarawa = 
	[
		{display: "Akwanga", value: "Akwanga"},
		{display: "Awe", value: "Awe"},	
		{display: "Doma", value: "Doma"}, 
		{display: "Karu", value: "Karu"},
		{display: "Keana", value: "Keana"},
		{display: "Keffi", value: "Keffi"},
		{display: "Kokona", value: "Kokona"},
		{display: "Lafia", value: "Lafia"},
		{display: "Nassarawa", value: "Nassarawa"},
		{display: "Nassarawa/Eggon", value: "Nassarawa/Eggon"},
		{display: "Obi", value: "Obi"},
		{display: "Toto", value: "Toto"},
		{display: "Wamba", value: "Wamba"}
	];

	var Niger = 
	[
		{display: "Agaie", value: "Agaie"},
		{display: "Agwara", value: "Agwara"},	
		{display: "Bida", value: "Bida"},
		{display: "Borgu", value: "Borgu"}, 
		{display: "Bosso", value: "Bosso"},
		{display: "Chanchanga", value: "Chanchanga"},
		{display: "Edati", value: "Edati"},
		{display: "Gbako", value: "Gbako"},
		{display: "Gurara", value: "Gurara"},
		{display: "Kitcha", value: "Kitcha"},
		{display: "Kontagora", value: "Kontagora"},
		{display: "Lapai", value: "Lapai"},
		{display: "Lavun", value: "Lavun"},
		{display: "Magama", value: "Magama"},
		{display: "Mariga", value: "Mariga"},
		{display: "Mokwa", value: "Mokwa"},
		{display: "Moshegu", value: "Moshegu"},
		{display: "Muya", value: "Muya"},
		{display: "Paiko", value: "Paiko"},
		{display: "Rafi", value: "Rafi"},
		{display: "Shiroro", value: "Shiroro"},
		{display: "Suleija", value: "Suleija"},
		{display: "Tawa-Wushishi", value: "Tawa-Wushishi"}
	];

	var Ogun = 
	[
		{display: "Abeokuta south", value: "Abeokuta south"},
		{display: "Abeokuta north", value: "Abeokuta north"},	
		{display: "Ado-odo/otta", value: "Ado-odo/otta"},
		{display: "Agbado south", value: "Agbado south"}, 
		{display: "Agbado north", value: "Agbado north"},
		{display: "Ewekoro", value: "Ewekoro"},
		{display: "Idarapo", value: "Idarapo"},
		{display: "Ifo", value: "Ifo"},
		{display: "Ijebu east", value: "Ijebu east"},
		{display: "Ijebu north", value: "Ijebu north"},
		{display: "Ikenne", value: "Ikenne"},
		{display: "Ilugun Alaro", value: "Ilugun Alaro"},
		{display: "Imeko afon", value: "Imeko afon"},
		{display: "Ipokia", value: "Ipokia"},
		{display: "Obafemi/owode", value: "Obafemi/owode"},
		{display: "Odeda", value: "Odeda"},
		{display: "Odogbolu", value: "Odogbolu"},
		{display: "Ogun waterside", value: "Ogun waterside"},
		{display: "Sagamu", value: "Sagamu"}
	];

	var Ondo = 
	[
		{display: "Akoko north", value: "Akoko north"},
		{display: "Akoko north east", value: "Akoko north east"},	
		{display: "Akoko south east", value: "Akoko south east"},
		{display: "Akoko south", value: "Akoko south"}, 
		{display: "Akure north", value: "Akure north"},
		{display: "Akure", value: "Akure"},
		{display: "Idanre", value: "Idanre"},
		{display: "Ifedore", value: "Ifedore"},
		{display: "Ese odo", value: "Ese odo"},
		{display: "Ilaje", value: "Ilaje"},
		{display: "Ilaje oke-igbo", value: "Ilaje oke-igbo"},
		{display: "Irele", value: "Irele"},
		{display: "Odigbo", value: "Odigbo"},
		{display: "Okitipupa", value: "Okitipupa"},
		{display: "Ondo", value: "Ondo"},
		{display: "Ondo east", value: "Ondo east"},
		{display: "Ose", value: "Ose"},
		{display: "Owo", value: "Owo"}
	];

	var Osun = 
	[
		{display: "Atakumosa west", value: "Atakumosa west"},
		{display: "Atakumosa east", value: "Atakumosa east"},	
		{display: "Ayeda-ade", value: "Ayeda-ade"}, 
		{display: "Ayedire", value: "Ayedire"},
		{display: "Bolawaduro", value: "Bolawaduro"},
		{display: "Boripe", value: "Boripe"},
		{display: "Ede", value: "Ede"},
		{display: "Ede north", value: "Ede north"},
		{display: "Egbedore", value: "Egbedore"},
		{display: "Ejigbo", value: "Ejigbo"},
		{display: "Ife north", value: "Ife north"},
		{display: "Ife central", value: "Ife central"},
		{display: "Ife south", value: "Ife south"},
		{display: "Ife east", value: "Ife east"},
		{display: "Ifedayo", value: "Ifedayo"},
		{display: "Ifelodun", value: "Ifelodun"},
		{display: "Ilesha west", value: "Ilesha west"},
		{display: "Ila-orangun", value: "Ila-orangun"},
		{display: "Ilesah east", value: "Ilesah east"},
		{display: "Irepodun", value: "Irepodun"},
		{display: "Irewole", value: "Irewole"},
		{display: "Isokan", value: "Isokan"},
		{display: "Iwo", value: "Iwo"},
		{display: "Obokun", value: "Obokun"},
		{display: "Odo-otin", value: "Odo-otin"},
		{display: "ola oluwa", value: "ola oluwa"},
		{display: "olorunda", value: "olorunda"},
		{display: "Oriade", value: "Oriade"},
		{display: "Orolu", value: "Orolu"},
		{display: "Osogbo", value: "Osogbo"}
	];

	var Oyo = 
	[
		{display: "Afijio", value: "Afijio"},
		{display: "Akinyele", value: "Akinyele"},	
		{display: "Attba", value: "Attba"}, 
		{display: "Atigbo", value: "Atigbo"},
		{display: "Egbeda", value: "Egbeda"},
		{display: "Ibadan", value: "Ibadan"},
		{display: "north east", value: "north east"},
		{display: "Ibadan central", value: "Ibadan central"},
		{display: "Ibadan south east", value: "Ibadan south east"},
		{display: "Ibadan west south", value: "Ibadan west south"},
		{display: "Ibarapa east", value: "Ibarapa east"},
		{display: "Ibarapa north", value: "Ibarapa north"},
		{display: "Ido", value: "Ido"},
		{display: "Ifedapo", value: "Ifedapo"},
		{display: "Ifeloju", value: "Ifeloju"},
		{display: "Irepo", value: "Irepo"},
		{display: "Iseyin", value: "Iseyin"},
		{display: "Itesiwaju", value: "Itesiwaju"},
		{display: "Iwajowa", value: "Iwajowa"},
		{display: "Iwajowa olorunshogo", value: "Iwajowa olorunshogo"},
		{display: "Kajola", value: "Kajola"},
		{display: "Lagelu", value: "Lagelu"},
		{display: "Ogbomosho north", value: "Ogbomosho north"},
		{display: "Ogbomosho south", value: "Ogbomosho south"},
		{display: "Ogo oluwa", value: "Ogo oluwa"},
		{display: "Oluyole", value: "Oluyole"},
		{display: "Ona ara", value: "Ona ara"},
		{display: "Ore lope", value: "Ore lope"},
		{display: "Orire", value: "Orire"},
		{display: "Oyo east", value: "Oyo east"},
		{display: "Oyo west", value: "Oyo west"},
		{display: "Saki east", value: "Saki east"},
		{display: "Saki west", value: "Saki west"},
		{display: "Surulere", value: "Surulere"}
	];

	var Plateau = 
	[
		{display: "Barkin/ladi", value: "Barkin/ladi"},
		{display: "Bassa", value: "Bassa"},	
		{display: "Bokkos", value: "Bokkos"}, 
		{display: "Jos northJos north", value: "Jos north"},
		{display: "Jos east", value: "Jos east"},
		{display: "Jos south", value: "Jos south"},
		{display: "Kanam", value: "Kanam"},
		{display: "kiyom", value: "kiyom"},
		{display: "Langtang north", value: "Langtang north"},
		{display: "Langtang south", value: "Langtang south"},
		{display: "Mangu", value: "Mangu"},
		{display: "Mikang", value: "Mikang"},
		{display: "Pankshin", value: "Pankshin"},
		{display: "Qua`an pan", value: "Qua`an pan"},
		{display: "Shendam", value: "Shendam"},
		{display: "Wase", value: "Wase"}
	];

	var Rivers = 
	[
		{display: "Abua/Odial", value: "Abua/Odial"},
		{display: "Ahoada west", value: "Ahoada west"},	
		{display: "Akuku toru", value: "Akuku toru"},
		{display: "Andoni", value: "Andoni"}, 
		{display: "Asari toru", value: "Asari toru"},
		{display: "Bonny", value: "Bonny"},
		{display: "Degema", value: "Degema"},
		{display: "Eleme", value: "Eleme"},
		{display: "Emohua", value: "Emohua"},
		{display: "Etche", value: "Etche"},
		{display: "Gokana", value: "Gokana"},
		{display: "Ikwerre", value: "Ikwerre"},
		{display: "Oyigbo", value: "Oyigbo"},
		{display: "Khana", value: "Khana"},
		{display: "Obio/Akpor", value: "Obio/Akpor"},
		{display: "Ogba east/Edoni", value: "Ogba east/Edoni"},
		{display: "Ogu/bolo", value: "Ogu/bolo"},
		{display: "Okrika", value: "Okrika"},
		{display: "Omumma", value: "Omumma"},
		{display: "Opobo/Nkoro", value: "Opobo/Nkoro"},
		{display: "Portharcourt", value: "Portharcourt"},
		{display: "Tai", value: "Tai"}
	];

	var Sokoto = 
	[
		{display: "Binji", value: "Binji"},
		{display: "Bodinga", value: "Bodinga"},	
		{display: "Dange/shuni", value: "Dange/shuni"},
		{display: "Gada", value: "Gada"}, 
		{display: "Goronyo", value: "Goronyo"},
		{display: "Gudu", value: "Gudu"},
		{display: "Gwadabawa", value: "Gwadabawa"},
		{display: "Illella", value: "Illella"},
		{display: "Kebbe", value: "Kebbe"},
		{display: "Kware", value: "Kware"},
		{display: "Rabah", value: "Rabah"},
		{display: "Sabon-Birni", value: "Sabon-Birni"},
		{display: "Shagari", value: "Shagari"},
		{display: "Silame", value: "Silame"},
		{display: "Sokoto south", value: "Sokoto south"},
		{display: "Sokoto north", value: "Sokoto north"},
		{display: "Tambuwal", value: "Tambuwal"},
		{display: "Tangaza", value: "Tangaza"},
		{display: "Tureta", value: "Tureta"},
		{display: "Wamakko", value: "Wamakko"},
		{display: "Wurno", value: "Wurno"},
		{display: "Yabo", value: "Yabo"}
	];

	var Taraba = 
	[
		{display: "Akdo-kola", value: "Akdo-kola"},
		{display: "Bali", value: "Bali"},	
		{display: "Donga", value: "Donga"}, 
		{display: "Gashaka", value: "Gashaka"},
		{display: "Gassol", value: "Gassol"},
		{display: "Ibi", value: "Ibi"},
		{display: "Jalingo", value: "Jalingo"},
		{display: "K/Lamido", value: "K/Lamido"},
		{display: "Kurmi", value: "Kurmi"},
		{display: "lan", value: "lan"},
		{display: "Sardauna", value: "Sardauna"},
		{display: "Tarum", value: "Tarum"},
		{display: "Ussa", value: "Ussa"},
		{display: "Wukari", value: "Wukari"},
		{display: "Yorro", value: "Yorro"},
		{display: "Zing", value: "Zing"}
	];

	var Yobe = 
	[
		{display: "Borsari", value: "Borsari"},
		{display: "Damaturu", value: "Damaturu"},
		{display: "Fika", value: "Fika"},
		{display: "Fune", value: "Fune"},
		{display: "Geidam", value: "Geidam"},
		{display: "Gogaram", value: "Gogaram"},
		{display: "Gujba", value: "Gujba"},
		{display: "Gulani", value: "Gulani"},
		{display: "Jakusko", value: "Jakusko"},
		{display: "Karasuwa", value: "Karasuwa"},
		{display: "Machina", value: "Machina"},
		{display: "Nagere", value: "Nagere"},
		{display: "Nguru", value: "Nguru"},
		{display: "Potiskum", value: "Potiskum"},	
		{display: "Tarmua", value: "Tarmua"}, 
		{display: "Yunusari", value: "Yunusari"},
		{display: "Yusufari", value: "Yusufari"},
		{display: "G ashua", value: "G ashua"}
	];

	var Zamfara = 
	[
		{display: "Anka", value: "Anka"},
		{display: "bukkuyum", value: "bukkuyum"},
		{display: "Dungudu", value: "Dungudu"},
		{display: "Chafe", value: "Chafe"},
		{display: "Gummi", value: "Gummi"},
		{display: "Gusau", value: "Gusau"},
		{display: "Isa", value: "Isa"},
		{display: "Kaura/Namoda", value: "Kaura/Namoda"},	
		{display: "Mradun", value: "Mradun"}, 
		{display: "Maru", value: "Maru"},
		{display: "Shinkafi", value: "Shinkafi"},
		{display: "Talata/Mafara", value: "Talata/Mafara"},
		{display: "Zumi", value: "Zumi"}
	];
	
	
	
	
	

	//Function executes on change of first select option field 
	$("#state").change(function() {
		var selected_state = $("#state option:selected").val();
		switch(selected_state) {
			case "Abuja":
				get_lgas(Abuja);
			break;

			case "Abia":
				get_lgas(Abia);
			break;

			case "Adamawa":
				get_lgas(Adamawa);
			break;

			case "Akwa Ibom":
				get_lgas(Akwa_Ibom);
			break;

			case "Anambra":
				get_lgas(Anambra);
			break;

			case "Bauchi":
				get_lgas(Bauchi);
			break;

			case "Bayelsa":
				get_lgas(Bayelsa);
			break;

			case "Benue":
				get_lgas(Benue);
			break;

			case "Borno":
				get_lgas(Borno);
			break;

			case "Cross River":
				get_lgas(Cross_River);
			break;

			case "Delta":
				get_lgas(Delta);
			break;

			case "Ebonyi":
				get_lgas(Ebonyi);
			break;

			case "Edo":
				get_lgas(Edo);
			break;

			case "Ekiti":
				get_lgas(Ekiti);
			break;

			case "Enugu":
				get_lgas(Enugu);
			break;

			case "Gombe":
				get_lgas(Gombe);
			break;

			case "Imo":
				get_lgas(Imo);
			break;

			case "Jigawa":
				get_lgas(Jigawa);
			break;

			case "Kaduna":
				get_lgas(Kaduna);
			break;

			case "Kano":
				get_lgas(Kano);
			break;

			case "Katsina":
				get_lgas(Katsina);
			break;

			case "Kebbi":
				get_lgas(Kebbi);
			break;

			case "Kogi":
				get_lgas(Kogi);
			break;

			case "Kwara":
				get_lgas(Kwara);
			break;

			case "Lagos":
				get_lgas(Lagos);
			break;

			case "Nasarawa":
				get_lgas(Nasarawa);
			break;

			case "Niger":
				get_lgas(Niger);
			break;

			case "Ogun":
				get_lgas(Ogun);
			break;

			case "Ondo":
				get_lgas(Ondo);
			break;

			case "Osun":
				get_lgas(Osun);
			break;

			case "Oyo":
				get_lgas(Oyo);
			break;

			case "Plateau":
				get_lgas(Plateau);
			break;

			case "Rivers":
				get_lgas(Rivers);
			break;

			case "Sokoto":
				get_lgas(Sokoto);
			break;

			case "Taraba":
				get_lgas(Taraba);
			break;

			case "Yobe":
				get_lgas(Yobe);
			break;

			case "Zamfara":
				get_lgas(Zamfara);
			break;

			default:
				$("#lga").empty();
				$("#lga").append("<option>--Select--</option>");
			break;
		}
	});





});

