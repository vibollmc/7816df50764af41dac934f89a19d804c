using System.Collections.Generic;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using JavCrawl.Dal.Context;

namespace JavCrawl.Controllers
{
    public class ViewFilmController : Controller
    {
        private readonly IDbRepository _dbRepository;
        public ViewFilmController(IDbRepository dbRepository)
        {
            _dbRepository = dbRepository;
        }
        [HttpGet]
        public IActionResult Index()
        {
            ViewBag.Message = "";
            var model = _dbRepository.GetFilmsToGenerateBigSlide();
            return View(model);
        }
        [HttpPost]
        public async Task<IActionResult> Index(IList<int> film)
        {
            var result = await _dbRepository.NewSlide(film);

            ViewBag.Message = result ? "Successful." : "Error";

            var model = _dbRepository.GetFilmsToGenerateBigSlide();

            return View(model);
        }

        [HttpPost]
        public async Task<IActionResult> Member()
        {
            await _dbRepository.GenerateMemberVideo();

            return RedirectToAction("Index");
        }
    }
}