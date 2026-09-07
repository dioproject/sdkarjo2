import { create } from "zustand";

type SiteSettings = {
  phone: string;
  email: string;
  address: string;
  setSettings: (settings: Omit<SiteSettings, "setSettings">) => void;
};

export const useSiteSettings = create<SiteSettings>((set) => ({
  phone: "0271-884-221",
  email: "info@sdnkarangjoho2.sch.id",
  address: "Jl. Pendidikan No. 12, Karangjoho, Sukoharjo",
  setSettings: (settings) => set(settings)
}));
