Rails.application.routes.draw do
  resources :images
  resources :tags
  get "/test" => "site#test"
end
