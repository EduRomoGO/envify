class SiteController < ApplicationController
  def test
    render json: ["Hola Romo", "Romitoooooooooo"].to_json
  end
end
