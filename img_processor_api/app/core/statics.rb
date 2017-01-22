class Statics
  GREEN_RATIO = 10

  def ratio
    fin = {}
    states.each do |state|
      result = 100 - (travel_per_inhabitant[state] * 100 + occupation[state])
      result += green[state].to_f
      fin[state] = result > 100 ? 100 : result.round(4)
    end
    f = fin.sort_by {|_key, value| -value}
    f.to_h
  end

  def travel_per_inhabitant
    fin = {}
    states.each do |state|
      fin[state] = (travellers[state].to_f / population[state]).round(4)
    end
    fin
  end

  def green
    {
      pontevedra: 0.78,
      barcelona: 0.63333,
      asturias: 0.6,
      las_palmas: 0.6666,
      cantabria: 0.73,
      lleida: 0.66666
    }
  end

  def states
    [:alava, :albacete, :alicante, :almeria, :asturias, :avila, :badajoz, :barcelona, :bizkaia, 
    :burgos, :caceres, :cadiz, :cantabria, :castellon, :ceuta, :ciudad_real, :cordoba, :coruña, 
    :cuenca, :gipuzkoa, :girona, :granada, :guadalajara, :huelva, :huesca, :islas_baleares, :jaen, 
    :la_rioja, :las_palmas, :leon, :lleida, :lugo, :madrid, :malaga, :melilla, :murcia, :navarra, 
    :ourense, :palencia, :pontevedra, :salamanca, :segovia, :sevilla, :soria, :tarragona, :tenerife, 
    :teruel, :toledo, :valencia, :valladolid, :zamora, :zaragoza]
  end

  def population
    {
      alava: 324126,
      albacete: 392118,
      alicante: 1836459,
      almeria: 704297,
      asturias: 990287,
      avila: 162514,
      badajoz: 684113,
      barcelona: 5542680,
      bizkaia: 1003313,
      burgos: 360995,
      caceres: 403665,
      cadiz: 1239889,
      cantabria: 582206,
      castellon: 579245,
      ceuta: 90504,
      ciudad_real: 506888,
      cordoba: 791610,
      coruña: 1122799,
      cuenca: 201071,
      gipuzkoa: 717832,
      girona: 465434,
      granada: 1060078,
      guadalajara: 202453,
      huelva: 519596,
      huesca: 221079,
      islas_baleares: 1107220,
      jaen: 648250,
      la_rioja: 315794,
      las_palmas: 1097800,
      leon: 587258,
      lleida: 359143,
      lugo: 336527,
      madrid: 6466996,
      malaga: 1629298,
      melilla: 93929,
      murcia: 1273152,
      navarra: 534925,
      ourense: 367042,
      palencia: 164644,
      pontevedra: 944346,
      salamanca: 335985,
      segovia: 155652,
      sevilla: 1939775,
      soria: 90040,
      tarragona: 792299,
      tenerife: 1004124,
      teruel: 136977,
      toledo: 688672,
      valencia: 2544264,
      valladolid: 523679,
      zamora: 281778,
      zaragoza: 791344
    }
  end

  def travellers
    {
      alava: 23886,
      albacete: 25335,
      alicante: 194870,
      almeria: 93307,
      asturias: 122681,
      avila: 24540,
      badajoz: 45872,
      barcelona: 288609,
      bizkaia: 70913,
      burgos: 46982,
      caceres: 49179,
      cadiz: 137707,
      cantabria: 80754,
      castellon: 8782,
      ceuta: 3950,
      ciudad_real: 30069,
      cordoba: 57671,
      coruña: 115236,
      cuenca: 2254,
      gipuzkoa: 51674,
      girona: 146926,
      granada: 126017,
      guadalajara: 21680,
      huelva: 69953,
      huesca: 58131,
      islas_baleares: 10284,
      jaen: 37090,
      la_rioja: 38858,
      las_palmas: 79224,
      leon: 53853,
      lleida: 54496,
      lugo: 41275,
      madrid: 515523,
      malaga: 171206,
      melilla: 3465,
      murcia: 82773,
      navarra: 61200,
      ourense: 23904,
      palencia: 12426,
      pontevedra: 87588,
      salamanca: 58097,
      segovia: 30243,
      sevilla: 130301,
      soria: 18183,
      tarragona: 145085,
      tenerife: 73965,
      teruel: 29432,
      toledo: 48386,
      valencia: 149467,
      valladolid: 47881,
      zamora: 17753,
      zaragoza: 91577
    }
  end

  def occupation
    {:alava=>44.9,
   :albacete=>28.64,
   :alicante=>69.27,
   :almeria=>46.55,
   :asturias=>38.47,
   :avila=>31.32,
   :badajoz=>29.99,
   :barcelona=>65.53,
   :bizkaia=>53.23,
   :burgos=>39.7,
   :caceres=>34.16,
   :cadiz=>50.15,
   :cantabria=>45.45,
   :castellon=>46.9,
   :ceuta=>64.28,
   :ciudad_real=>24.78,
   :cordoba=>47.35,
   :coruña=>38.0,
   :cuenca=>27.09,
   :gipuzkoa=>57.5,
   :girona=>50.74,
   :granada=>50.24,
   :guadalajara=>27.42,
   :huelva=>44.31,
   :huesca=>35.85,
   :islas_baleares=>68.94,
   :jaen=>29.68,
   :la_rioja=>45.67,
   :las_palmas=>79.27,
   :leon=>33.98,
   :lleida=>30.81,
   :lugo=>30.61,
   :madrid=>57.44,
   :malaga=>63.16,
   :melilla=>51.96,
   :murcia=>49.85,
   :navarra=>43.45,
   :ourense=>26.94,
   :palencia=>32.78,
   :pontevedra=>36.26,
   :salamanca=>44.27,
   :segovia=>35.9,
   :sevilla=>56.5,
   :soria=>32.25,
   :tarragona=>49.78,
   :tenerife=>78.77,
   :teruel=>26.27,
   :toledo=>36.98,
   :valencia=>51.88,
   :valladolid=>38.1,
   :zamora=>30.14,
   :zaragoza=>42.62}
  end
end


# states.each do |s|
#   fin[s] = (travellers[s].to_f / population[s]).round(4)  
# end  

