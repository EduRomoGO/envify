class CognitiveServices
  require 'base64'

  attr_reader :image

  def initialize(image)
    @image = image
  end

  def tag
    uri = URI("https://westus.api.cognitive.microsoft.com/vision/v1.0/tag?maxCandidates=5")
    uri.query = URI.encode_www_form({
      'visualFeatures' => 'Categories',
      'language' => 'en'
    })

    request = Net::HTTP::Post.new(uri.request_uri)
    request['Content-Type'] = 'application/octet-stream'
    request['Ocp-Apim-Subscription-Key'] = "ec7d7a74a1f64f3d951d7bc746edf6d7"

    # Request body
    request.body = File.open("#{image.url}", "rb").read

    response = Net::HTTP.start(uri.host, uri.port, :use_ssl => uri.scheme == 'https') do |http|
      http.request(request)
    end
    
    JSON.parse(response.body)["tags"].map{|c| c["name"]}
  end
end