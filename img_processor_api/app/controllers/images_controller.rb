class ImagesController < ApplicationController
  def create
    uploaded_io = params[:file]
    File.open(Rails.root.join('public', 'uploads', uploaded_io.original_filename), 'wb') do |file|
      file.write(uploaded_io.read)
    end

    image = Image.create(path: "/uploads/#{uploaded_io.original_filename}")

    cs = CognitiveServices.new(image).tag
    render json: cs.to_json
  end
end
