// Shipping rates based on regions (matches backend ShippingRate model)
export interface ShippingRate {
    id: number;
    region: string;
    price_per_kg: number;
}

// Calculate weight based on number of items
// Rule: 1 kg = 3 kaos, kurang dari 3 tetap dihitung 1 kg
export const calculateWeight = (totalItems: number): number => {
    return Math.ceil(totalItems / 3);
};

// Calculate shipping cost
export const calculateShippingCost = (totalItems: number, pricePerKg: number): number => {
    const weight = calculateWeight(totalItems);
    return weight * pricePerKg;
};

// Get shipping rate by region
export const getShippingRateByRegion = (
    region: string,
    shippingRates: ShippingRate[]
): ShippingRate | undefined => {
    return shippingRates.find((rate) => rate.region === region);
};

// Format weight display
export const formatWeight = (totalItems: number): string => {
    const weight = calculateWeight(totalItems);
    return `${weight} kg (${totalItems} pcs)`;
};
