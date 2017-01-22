class RatiosController < ApplicationController
  def index
    s = Statics.new.ratio
    render json: s
  end
end
