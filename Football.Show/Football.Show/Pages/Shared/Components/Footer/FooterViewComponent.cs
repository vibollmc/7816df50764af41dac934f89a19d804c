using Football.Show.Dal.Context;
using Microsoft.AspNetCore.Mvc;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace Football.Show.Pages.Shared.Components.Footer
{
    [ViewComponent(Name = "Footer")]
    public class FooterViewComponent: ViewComponent
    {
        private readonly ICategoryRepository _categoryRepository;
        public FooterViewComponent(ICategoryRepository categoryRepository)
        {
            _categoryRepository = categoryRepository;
        }

        public async Task<IViewComponentResult> InvokeAsync()
        {
            var categories = await _categoryRepository.GetCategories();

            return View(categories);
        }
    }
}
